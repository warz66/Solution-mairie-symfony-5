<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\EvenementType;
use App\Form\PublicationType;
use App\Repository\CategoryRepository;
use App\Service\ImgToPublicationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use App\Service\MessageDependenciesService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminEvenementController extends AbstractController
{
    /**
     * Index des événements
     * 
     * @Route("/admin/evenement/{page<\d+>?1}", name="admin_evenement_index")
     *
     */
    public function index($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request, MessageDependenciesService $md) {

        // on crée la pagination
        $nbEvenenements = 30;
        $data = $repo->findCategory('evenement', 0);
        $evenements = $paginator->paginate($data, $request->query->getInt('page',$page), $nbEvenenements);
        $evenements->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/evenement/index.html.twig', [
            'evenements' => $evenements,
            'PublicationsWithDependencies' => $md->getMsgPublicationsDependencies($evenements->getItems()),
        ]);
    }

    /**
     * @Route("/admin/evenement/new", name="admin_evenement_new")
     */
    public function new(EntityManagerInterface $manager, Request $request, CategoryRepository $CategoryRepo)
    {   
        $evenement = new Publication();
        $form = $this->createForm(PublicationType::class, $evenement);
        $form->add('evenement', EvenementType::class, ['label' => false]);
        $form->get('evenement')->remove('statut');
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                $evenement->setCreateAt(new \DateTime('now'))
                         ->setUpdatedAt($evenement->getCreateAt())
                         ->setCategory($CategoryRepo->findOneBy(['nom' => 'evenement']))
                         ->getEvenement()->setStatut(0);
                $manager->persist($evenement);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'événement <strong>{$evenement->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_evenement_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, l'événement n'a pu être sauvegardé, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/evenement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'éditer un événement
     * 
     * @Route("/admin/evenement/{id}/edit", name="admin_evenement_edit")
     * 
     */
    public function edit(Publication $evenement, EntityManagerInterface $manager, Request $request, ImgToPublicationService $imgToPublication)
    {   
        
        if ($evenement->getCategory()->getNom() !== 'evenement') {
            throw $this->createNotFoundException("Cette événement n'existe pas");
        }
        $form = $this->createForm(PublicationType::class, $evenement);
        $form->add('evenement', EvenementType::class, ['label' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
        
                $imgToPublication->imgPublicationToBdd($evenement); // va gérer les images du content de la publication dans la bdd

                $manager->persist($evenement);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "L'événement <strong>{$evenement->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_evenement_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, cette événement n'a pu être sauvegardé, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/evenement/edit.html.twig', [
            'trash' => ($request->get('trash')) ? true : false,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Index des évènements dans la corbeille
     * 
     * @Route("/admin/evenement/trash/{page<\d+>?1}", name="admin_evenement_trash_index")
     *
     */
    public function trashIndex($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request) {

        // on crée la pagination
        $nbPage = 30;
        $data = $repo->findCategory('evenement', 1);
        $evenements = $paginator->paginate($data, $request->query->getInt('page',$page), $nbPage);
        $evenements->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/evenement/trash.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    /**
     * Permet d'envoyer un évènement à la corbeille
     * 
     * @Route("/admin/evenement/{id}/trash", name="admin_evenement_trash")
     *
     */
    public function trash(Publication $evenement, EntityManagerInterface $manager) {

        $evenement->setTrash(1);
        $manager->persist($evenement);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'évènement <strong>{$evenement->getTitle()}</strong> a bien été envoyée à la corbeille !"
        );

        return $this->redirectToRoute("admin_evenement_index");
    }

    /**
     * Permet de restaurer un évènement
     * 
     * @Route("/admin/evenement/{id}/restore", name="admin_evenement_restore")
     *
     */
    public function restore(Publication $evenement, EntityManagerInterface $manager) {

        $evenement->setTrash(0);
        $evenement->setStatut(0);
        $manager->persist($evenement);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'évènement <strong>{$evenement->getTitle()}</strong> a bien été restaurée"
        );

        return $this->redirectToRoute("admin_evenement_trash_index");
    }

    /**
     * Permet de supprimer un évènement
     * 
     * @Route("/admin/evenement/{id}/delete", name="admin_evenement_delete")
     *
     */
    public function delete(Publication $evenement, EntityManagerInterface $manager) {

        $manager->remove($evenement);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'évènement <strong>{$evenement->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_evenement_trash_index");
    }

    /**
     * Permet de vider la corbeille
     * 
     * @Route("/admin/evenement/trash/empty", name="admin_evenement_trash_empty")
     *
     */
    public function trashEmpty(EntityManagerInterface $manager, PublicationRepository $repo) {

        $evenements = $repo->findCategory('evenement', 1);

        foreach ($evenements as $evenement) {
            $manager->remove($evenement);
            $manager->flush();
        }
        
        $this->addFlash(
            'success',
            "La corbeille a bien été vidée !"
        );

        return $this->redirectToRoute("admin_evenement_trash_index");
    }

    /**
     * Permet de changer le statut de publication de l'événement (requête Ajax)
     * 
     * @Route("/admin/evenement/{id}/statut", name="admin_evenement_statut")
     */
    public function statut(Publication $evenement, EntityManagerInterface $manager) {
        $statut=$_POST['statut'];
        if($statut=='true') {
            $evenement->setStatut(true);
            $manager->flush();
            return $this->json(true); 
        } else {
            $evenement->setStatut(false);
            $manager->flush();
            return $this->json(false);
        }
    }

    /**
     * @Route("/admin/evenement/{id}/preview", name="admin_evenement_preview")
     */
    public function preview(CacheManager $cacheManager, Publication $evenement = null, Request $request) {

        // cas où on est dans le new 
        if (null === $evenement) {
            $evenement = new Publication();
        }
        $form = $this->createForm(PublicationType::class, $evenement);
        $form->add('evenement', EvenementType::class, ['label' => false]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                // on gere l'image de couverture en mettant le fichier avec le nom tempImgFilePreview et l'extenssion approprié, cette image sera écraser si elle existe à chaque prévisualisation.
                $coverImgFile = $form->get('imageFile')->getData();
                if($coverImgFile) {
                    $newFilename = 'tempImgFilePreview.'.$coverImgFile->guessExtension();
                    $pathFile = $this->getParameter('kernel.project_dir').'/public'.$this->getParameter('publication_cover_path').$newFilename;
                    if(file_exists($pathFile)) {
                        $cacheManager->remove($this->getParameter('publication_cover_path') . $newFilename);
                        unlink($pathFile); 
                    }
                    try {
                        $coverImgFile->move(substr($this->getParameter('publication_cover_path'),1),$newFilename); // faire mieux 
                    }catch (FileException $e) {
                        throw new HttpException(500,"L'upload de l'image de couverture a échoué : " . $e->getMessage());
                    }
                    $evenement->setCoverImage($newFilename);
                }

                // on gere les fichiers ressources
                $ressources = $evenement->getRessources()->getValues();
                $i=0;
                foreach($ressources as $ressource) {
                    $ressourceFile = $ressource->getUrlFile();
                    if($ressourceFile) {
                        $newFilename = 'tempRscFilePreview' .$i. '.' .$ressourceFile->guessExtension();
                        $i++;
                        $pathFile = $this->getParameter('kernel.project_dir').'/public'.$this->getParameter('ressources_path').$newFilename;
                        if(file_exists($pathFile)) {
                            unlink($pathFile); 
                        }
                        try {
                            $ressourceFile->move(substr($this->getParameter('ressources_path'),1),$newFilename); // faire mieux 
                        }catch (FileException $e) {
                            throw new HttpException(500,"L'upload d'une ressource a échoué : " . $e->getMessage());
                        }
                        $ressource->setUrl($newFilename);
                        $evenement->removeRessource($ressource);
                        $evenement->addRessource($ressource);
                    }    
                }

            } else {
               /*throw new HttpException(500,"La page que vous souhaitez prévisualiser contient une ou des erreurs, vérifier notamment si l'image de couverture ne dépasse pas les 1 Mo.");*/
               $this->addFlash(
                    'danger',
                    "Attention, il se peut que le rendu de la page ne soit pas correct, vérifier si le formulaire est correctement rempli, et vérifier notamment si l'image de couverture ne dépasse pas les 1 Mo."
                );
            }
        }
        $response = $this->forward('App\Controller\PublicationController::evenement', [
            'evenement' => $evenement,
            'preview' => true
        ]);
        return $response;
    }
}
