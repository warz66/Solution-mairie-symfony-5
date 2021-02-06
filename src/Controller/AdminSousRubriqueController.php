<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\SousRubriqueType;
use App\Form\RubriqueExtendType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use App\Service\MessageDependenciesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSousRubriqueController extends AbstractController
{
    /**
     * Index des sous-rubriques
     * 
     * @Route("/admin/sous-rubrique", name="admin_sous-rubrique_index")
     *
     */
    public function index(PublicationRepository $repo, MessageDependenciesService $md) {

        $sousRubriques = $repo->findCategory('sous-rubrique', 0);

        return $this->render('admin/sous-rubrique/index.html.twig', [
            'sousRubriques' => $sousRubriques,
            'sousRubriquesWithDependencies' => $md->getMsgSousRubriquesDependencies($sousRubriques),
        ]);
    }

    /**
     * @Route("/admin/sous-rubrique/new", name="admin_sous-rubrique_new")
     */
    public function new(EntityManagerInterface $manager, Request $request, CategoryRepository $CategoryRepo)
    {   
        $sousRubrique = new Publication();
        $form = $this->createForm(SousRubriqueType::class, $sousRubrique);
        $form->add('rubrique', RubriqueExtendType::class, ['label' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $sousRubrique->setCreateAt(new \DateTime('now'))
                         ->setUpdatedAt($sousRubrique->getCreateAt())
                         ->setStatut(1)
                         ->setCategory($CategoryRepo->findOneBy(['nom' => 'sous-rubrique']));
                $manager->persist($sousRubrique);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "La sous-rubrique <strong>{$sousRubrique->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_sous-rubrique_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la sous-rubrique n'a pu être sauvegardée, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/sous-rubrique/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'éditer une sous-rubrique
     * 
     * @Route("/admin/sous-rubrique/{id}/edit", name="admin_sous-rubrique_edit")
     * 
     */
    public function edit(Publication $sousRubrique, EntityManagerInterface $manager, Request $request)
    {   

        if ($sousRubrique->getCategory()->getNom() !== 'sous-rubrique') {
            throw $this->createNotFoundException("Cette rubrique n'existe pas");
        }
        $form = $this->createForm(SousRubriqueType::class, $sousRubrique);
        $form->add('rubrique', RubriqueExtendType::class, ['label' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($sousRubrique);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "La sous-rubrique <strong>{$sousRubrique->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_sous-rubrique_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la sous-rubrique n'a pu être sauvegardée, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/sous-rubrique/edit.html.twig', [
            'trash' => ($request->get('trash')) ? true : false,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Index des sous-rubriques dans la corbeille
     * 
     * @Route("/admin/sous-rubrique/trash", name="admin_sous-rubrique_trash_index")
     *
     */
    public function trashIndex(PublicationRepository $repo) {

        return $this->render('admin/sous-rubrique/trash.html.twig', [
            'sousRubriques' => $repo->findCategory('sous-rubrique', 1)
        ]);
    }

    /**
     * Permet d'envoyer une sous-rubrique à la corbeille
     * 
     * @Route("/admin/sous-rubrique/{id}/trash", name="admin_sous-rubrique_trash")
     *
     */
    public function trash(Publication $sousRubrique, EntityManagerInterface $manager) {
        
        $sousRubrique->setTrash(1);
        $manager->persist($sousRubrique);
        $manager->flush();

        $this->addFlash(
            'success',
            "La sous-rubrique <strong>{$sousRubrique->getTitle()}</strong> a bien été envoyée à la corbeille !"
        );

        return $this->redirectToRoute("admin_sous-rubrique_index");
    }

    /**
     * Permet de restaurer une sous-rubrique
     * 
     * @Route("/admin/sous-rubrique/{id}/restore", name="admin_sous-rubrique_restore")
     *
     */
    public function restore(Publication $sousRubrique, EntityManagerInterface $manager) {

        $sousRubrique->setTrash(0);
        $manager->persist($sousRubrique);
        $manager->flush();

        $this->addFlash(
            'success',
            "La sous-rubrique <strong>{$sousRubrique->getTitle()}</strong> a bien été restaurée"
        );

        return $this->redirectToRoute("admin_sous-rubrique_trash_index");
    }

    /**
     * Permet de supprimer une sous-rubrique définitivement
     * 
     * @Route("/admin/sous-rubrique/{id}/delete", name="admin_sous-rubrique_delete")
     *
     */
    public function delete(Publication $sousRubrique, EntityManagerInterface $manager) {

        $manager->remove($sousRubrique);
        $manager->flush();

        $this->addFlash(
            'success',
            "La sous-rubrique <strong>{$sousRubrique->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_sous-rubrique_trash_index");
    }

    /**
     * Permet de vider la corbeille
     * 
     * @Route("/admin/sous-rubrique/trash/empty", name="admin_sous-rubrique_trash_empty")
     *
     */
    public function trashEmpty(EntityManagerInterface $manager, PublicationRepository $repo) {

        $sousRubriques = $repo->findCategory('sous-rubrique', 1);

        foreach ($sousRubriques as $sousRubrique) {
            $manager->remove($sousRubrique);
            $manager->flush();
        }
        
        $this->addFlash(
            'success',
            "La corbeille a bien été vidée !"
        );

        return $this->redirectToRoute("admin_sous-rubrique_trash_index");
    }

}

