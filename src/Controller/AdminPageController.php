<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\CategoryRepository;
use App\Service\ImgToPublicationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use App\Service\MessageDependenciesService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminPageController extends AbstractController
{

    /**
     * Index des pages
     * 
     * @Route("/admin/page/{page<\d+>?1}", name="admin_page_index")
     *
     */
    public function index($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request, MessageDependenciesService $md) {

        // on crée la pagination
        $nbPage = 30;
        $data = $repo->findCategory('page', 0);
        $pages = $paginator->paginate($data, $request->query->getInt('page',$page), $nbPage);
        $pages->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/page/index.html.twig', [
            'pages' => $pages,
            'PublicationsWithDependencies' => $md->getMsgPublicationsDependencies($pages->getItems()),
        ]);
    }

    /**
     * @Route("/admin/page/new", name="admin_page_new")
     */
    public function new(EntityManagerInterface $manager, Request $request, CategoryRepository $CategoryRepo)
    {   
        
        $page = new Publication();
        $form = $this->createForm(PublicationType::class, $page);
        $form->add('parent', EntityType::class, [
            'class' => Publication::class,
            'query_builder' => function (PublicationRepository $repo) {
                return $repo->findAllRubrique();
            },
            'group_by' => 'category.Nom',
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
            'placeholder' => 'Aucun parent',
            'empty_data' => null
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                $page->setCreateAt(new \DateTime('now'))
                         ->setUpdatedAt($page->getCreateAt())
                         ->setCategory($CategoryRepo->findOneBy(['nom' => 'page']));
                $manager->persist($page);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "La page <strong>{$page->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_page_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la page n'a pu être sauvegardée, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/page/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'éditer une page
     * 
     * @Route("/admin/page/{id}/edit", name="admin_page_edit")
     * 
     */
    public function edit(Publication $page, EntityManagerInterface $manager, Request $request, ImgToPublicationService $imgToPublication)
    {   

        if ($page->getCategory()->getNom() !== 'page') {
            throw $this->createNotFoundException("Cette page n'existe pas");
        }
        $form = $this->createForm(PublicationType::class, $page);
        $form->add('parent', EntityType::class, [
            'class' => Publication::class,
            'query_builder' => function (PublicationRepository $repo) {
                return $repo->findAllRubrique();
            },
            'group_by' => 'category.Nom',
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
            'placeholder' => 'Aucun parent',
            'empty_data' => null
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
        
                $imgToPublication->imgPublicationToBdd($page); // va gérer les images du content de la publication dans la bdd

                $manager->persist($page);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "La page <strong>{$page->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_page_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la page n'a pu être sauvegardée, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/page/edit.html.twig', [
            'trash' => ($request->get('trash')) ? true : false,
            'form' => $form->createView(),
            //'page' => $page,
        ]);
    }

    /**
     * Index des pages de la corbeille
     * 
     * @Route("/admin/page/trash/{page<\d+>?1}", name="admin_page_trash_index")
     *
     */
    public function trashIndex($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request) {

        // on crée la pagination
        $nbPage = 30;
        $data = $repo->findCategory('page', 1);
        $pages = $paginator->paginate($data, $request->query->getInt('page',$page), $nbPage);
        $pages->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/page/trash.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * Permet d'envoyer une page à la corbeille
     * 
     * @Route("/admin/page/{id}/trash", name="admin_page_trash")
     *
     */
    public function trash(Publication $page, EntityManagerInterface $manager) {

        $page->setTrash(1);
        $manager->persist($page);
        $manager->flush();

        $this->addFlash(
            'success',
            "La page <strong>{$page->getTitle()}</strong> a bien été envoyée à la corbeille !"
        );

        return $this->redirectToRoute("admin_page_index");
    }

    /**
     * Permet de restaurer une page
     * 
     * @Route("/admin/page/{id}/restore", name="admin_page_restore")
     *
     */
    public function restore(Publication $page, EntityManagerInterface $manager) {

        $page->setTrash(0);
        $page->setStatut(0);
        $manager->persist($page);
        $manager->flush();

        $this->addFlash(
            'success',
            "La page <strong>{$page->getTitle()}</strong> a bien été restaurée"
        );

        return $this->redirectToRoute("admin_page_trash_index");
    }

    /**
     * Permet de supprimer une page
     * 
     * @Route("/admin/page/{id}/delete", name="admin_page_delete")
     *
     */
    public function delete(Publication $page, EntityManagerInterface $manager) {

        $manager->remove($page);
        $manager->flush();

        $this->addFlash(
            'success',
            "La page <strong>{$page->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_page_trash_index");
    }

    /**
     * Permet de vider la corbeille
     * 
     * @Route("/admin/page/trash/empty", name="admin_page_trash_empty")
     *
     */
    public function trashEmpty(EntityManagerInterface $manager, PublicationRepository $repo) {

        $pages = $repo->findCategory('page', 1);

        foreach ($pages as $page) {
            $manager->remove($page);
            $manager->flush();
        }
        
        $this->addFlash(
            'success',
            "La corbeille a bien été vidée !"
        );

        return $this->redirectToRoute("admin_page_trash_index");
    }

    /**
     * Permet de changer le statut de publication de la page (requête Ajax)
     * 
     * @Route("/admin/page/{id}/statut", name="admin_page_statut")
     */
    public function statut(Publication $page, EntityManagerInterface $manager) {
        $statut=$_POST['statut'];
        if($statut=='true') {
            $page->setStatut(true);
            $manager->flush();
            return $this->json(true); 
        } else {
            $page->setStatut(false);
            $manager->flush();
            return $this->json(false);
        }
    }

    /**
     * @Route("/admin/page/{id}/preview", name="admin_page_preview")
     */
    public function preview(CacheManager $cacheManager, Publication $page = null, Request $request) {

        // cas où on est dans le new 
        if (null === $page) {
            $page = new Publication();
        }
        $form = $this->createForm(PublicationType::class, $page);
        $form->add('parent', EntityType::class, [
            'class' => Publication::class,
            'query_builder' => function (PublicationRepository $repo) {
                return $repo->findAllRubrique();
            },
            'group_by' => 'category.Nom',
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
            'placeholder' => 'Aucun parent',
            'empty_data' => null
        ]);
        
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
                    $page->setCoverImage($newFilename);
                }

                // on gere les fichiers ressources
                $ressources = $page->getRessources()->getValues();
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
                        $page->removeRessource($ressource);
                        $page->addRessource($ressource);
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
        $response = $this->forward('App\Controller\PublicationController::page', [
            'page'  => $page,
            'preview' => true
        ]);
        return $response;
    }
}
