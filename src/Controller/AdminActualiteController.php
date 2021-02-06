<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\ActualiteType;
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

class AdminActualiteController extends AbstractController
{
    /**
     * Index des actualités
     * 
     * @Route("/admin/actualite/{page<\d+>?1}", name="admin_actualite_index")
     *
     */
    public function index($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request, MessageDependenciesService $md) {

        // on crée la pagination
        $nbActualites = 30;
        $data = $repo->findCategory('actualite', 0);
        $actualites = $paginator->paginate($data, $request->query->getInt('page',$page), $nbActualites);
        $actualites->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/actualite/index.html.twig', [
            'actualites' => $actualites,
            'PublicationsWithDependencies' => $md->getMsgPublicationsDependencies($actualites->getItems()),
        ]);
    }

    /**
     * @Route("/admin/actualite/new", name="admin_actualite_new")
     */
    public function new(EntityManagerInterface $manager, Request $request, CategoryRepository $CategoryRepo)
    {   
        $actualite = new Publication();
        $form = $this->createForm(PublicationType::class, $actualite);
        $form->add('actualite', ActualiteType::class, ['label' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                $actualite->setCreateAt(new \DateTime('now'))
                         ->setUpdatedAt($actualite->getCreateAt())
                         ->setCategory($CategoryRepo->findOneBy(['nom' => 'actualite']));
                $manager->persist($actualite);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'actualité' <strong>{$actualite->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_actualite_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, l'actualité n'a pu être sauvegardée, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/actualite/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'éditer une actualité
     * 
     * @Route("/admin/actualite/{id}/edit", name="admin_actualite_edit")
     * 
     */
    public function edit(Publication $actualite, EntityManagerInterface $manager, Request $request, ImgToPublicationService $imgToPublication)
    {   
        
        if ($actualite->getCategory()->getNom() !== 'actualite') {
            throw $this->createNotFoundException("Cette actualité n'existe pas");
        }
        $form = $this->createForm(PublicationType::class, $actualite);
        $form->add('actualite', ActualiteType::class, ['label' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
        
                $imgToPublication->imgPublicationToBdd($actualite); // va gérer les images du content de la publication dans la bdd

                $manager->persist($actualite);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "L'actualité <strong>{$actualite->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_actualite_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, cette actualité n'a pu être sauvegardée, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/actualite/edit.html.twig', [
            'trash' => ($request->get('trash')) ? true : false,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Index de la corbeille 
     * 
     * @Route("/admin/actualite/trash/{page<\d+>?1}", name="admin_actualite_trash_index")
     *
     */
    public function trashIndex($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request, MessageDependenciesService $md) {

        // on crée la pagination
        $nbActualites = 30;
        $data = $repo->findCategory('actualite', 1);
        $actualites = $paginator->paginate($data, $request->query->getInt('page',$page), $nbActualites);
        $actualites->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/actualite/trash.html.twig', [
            'actualites' => $actualites,
        ]);
    }

    /**
     * Permet d'envoyer une actualité à la corbeille
     * 
     * @Route("/admin/actualite/{id}/trash", name="admin_actualite_trash")
     *
     */
    public function trash(Publication $actualite, EntityManagerInterface $manager) {

        $actualite->setTrash(1);
        $manager->persist($actualite);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'actualité <strong>{$actualite->getTitle()}</strong> a bien été envoyée à la corbeille !"
        );

        return $this->redirectToRoute("admin_actualite_index");
    }

    /**
     * Permet de restaurer une actualité
     * 
     * @Route("/admin/actualite/{id}/restore", name="admin_actualite_restore")
     *
     */
    public function restore(Publication $actualite, EntityManagerInterface $manager) {

        $actualite->setTrash(0);
        $manager->persist($actualite);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'actualité <strong>{$actualite->getTitle()}</strong> a bien été restaurée"
        );

        return $this->redirectToRoute("admin_actualite_trash_index");
    }

    /**
     * Permet de supprimer une actualité
     * 
     * @Route("/admin/actualite/{id}/delete", name="admin_actualite_delete")
     *
     */
    public function delete(Publication $actualite, EntityManagerInterface $manager) {

        $manager->remove($actualite);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'actualité' <strong>{$actualite->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_actualite_trash_index");
    }

    /**
     * Permet de vider la corbeille
     * 
     * @Route("/admin/actualite/trash/empty", name="admin_actualite_trash_empty")
     *
     */
    public function trashEmpty(EntityManagerInterface $manager, PublicationRepository $repo) {

        $actualites = $repo->findCategory('actualite', 1);

        foreach ($actualites as $actualite) {
            $manager->remove($actualite);
            $manager->flush();
        }
        
        $this->addFlash(
            'success',
            "La corbeille a bien été vidée !"
        );

        return $this->redirectToRoute("admin_actualite_trash_index");
    }

    /**
     * Permet de changer le statut de publication de l'actualité (requête Ajax)
     * 
     * @Route("/admin/actualite/{id}/statut", name="admin_actualite_statut")
     */
    public function statut(Publication $actualite, EntityManagerInterface $manager) {
        $statut=$_POST['statut'];
        if($statut=='true') {
            $actualite->setStatut(true);
            $manager->flush();
            return $this->json(true); 
        } else {
            $actualite->setStatut(false);
            $manager->flush();
            return $this->json(false);
        }
    }

    /**
     * @Route("/admin/actualite/{id}/preview", name="admin_actualite_preview")
     */
    public function preview(CacheManager $cacheManager, Publication $actualite = null, Request $request) {

        // cas où on est dans le new 
        if (null === $actualite) {
            $actualite = new Publication();
        }
        $form = $this->createForm(PublicationType::class, $actualite);
        $form->add('actualite', ActualiteType::class, ['label' => false]);
        
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
                    $actualite->setCoverImage($newFilename);
                }

                // on gere les fichiers ressources
                $ressources = $actualite->getRessources()->getValues();
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
                        $actualite->removeRessource($ressource);
                        $actualite->addRessource($ressource);
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
        $response = $this->forward('App\Controller\PublicationController::actualite', [
            'actualite' => $actualite,
            'preview' => true
        ]);
        return $response;
    }
}
