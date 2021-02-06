<?php

namespace App\Controller;

use App\Form\AccesRapideType;
use App\Entity\AccesRapideObjet;
use App\Form\AccesRapideObjetType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AccesRapideRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AccesRapideObjetRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAccesRapideController extends AbstractController
{
    /**
     * @Route("/admin/acces-rapide", name="admin_acces-rapide_index")
     */
    public function index(AccesRapideRepository $repoAccesRapide, AccesRapideObjetRepository $repoAccesRapideObjet, Request $request, EntityManagerInterface $manager) {

        $accesRapide = $repoAccesRapide->findBy([], ['id'=>'ASC'], 1, 0);
        $formAccesRapide = $this->createForm(AccesRapideType::class, $accesRapide[0]);
        $formAccesRapide->handleRequest($request);
        
        if ($formAccesRapide->isSubmitted()) {
            if ($formAccesRapide->isValid()) {

                $manager->persist($accesRapide[0]);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "L'accès rapide a bien été modifié !"
                );
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, l'accès rapide comporte des erreurs !"
                );
            }
        }

        return $this->render('admin/acces-rapide/index.html.twig', [
            'formAccesRapide' => $formAccesRapide->createView(),
            'accesRapideObjets' => $repoAccesRapideObjet->findBy([],['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/admin/acces-rapide/new", name="admin_acces-rapide_new")
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {   
        $accesRapideObjet = new AccesRapideObjet();
        $form = $this->createForm(AccesRapideObjetType::class, $accesRapideObjet);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($accesRapideObjet);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'objet lié à l'accès rapide <strong>{$accesRapideObjet->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_acces-rapide_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'objet lié à l'accès rapide n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/acces-rapide/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/acces-rapide/{id}/edit", name="admin_acces-rapide_edit")
     * 
     */
    public function edit(AccesRapideObjet $accesRapideObjet, EntityManagerInterface $manager, Request $request)
    {   
        
        $form = $this->createForm(AccesRapideObjetType::class, $accesRapideObjet);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                $manager->persist($accesRapideObjet);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "L'objet lié à l'accès rapide <strong>{$accesRapideObjet->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_acces-rapide_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'objet lié à l'accès rapide n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/acces-rapide/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/acces-rapide/{id}/delete", name="admin_acces-rapide_delete")
     */
    public function delete(AccesRapideObjet $accesRapideObjet, EntityManagerInterface $manager) {

        $manager->remove($accesRapideObjet);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'objet lié à l'accès rapide <strong>{$accesRapideObjet->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_acces-rapide_index");
    }
}
