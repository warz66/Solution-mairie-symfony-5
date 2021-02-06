<?php

namespace App\Controller;

use App\Form\CarrouselType;
use App\Entity\CarrouselObjet;
use App\Form\CarrouselObjetType;
use App\Repository\CarrouselRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CarrouselObjetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCarrouselController extends AbstractController
{
    /**
     * @Route("/admin/carrousel", name="admin_carrousel_index")
     */
    public function index(CarrouselRepository $repoCarrousel, CarrouselObjetRepository $repoCarrouselObjet, Request $request, EntityManagerInterface $manager) {

        $carrousel = $repoCarrousel->findBy([], ['id'=>'ASC'], 1, 0);
        $formCarrousel = $this->createForm(CarrouselType::class, $carrousel[0]);
        $formCarrousel->handleRequest($request);
        
        if ($formCarrousel->isSubmitted()) {
            if ($formCarrousel->isValid()) {

                $manager->persist($carrousel[0]);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "Le carrousel a bien été modifié !"
                );
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, le carrousel comporte des erreurs !"
                );
            }
        }

        return $this->render('admin/carrousel/index.html.twig', [
            'formCarrousel' => $formCarrousel->createView(),
            'carrouselObjets' => $repoCarrouselObjet->findBy([],['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/admin/carrousel/new", name="admin_carrousel_new")
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {   
        $carrouselObjet = new CarrouselObjet();
        $form = $this->createForm(CarrouselObjetType::class, $carrouselObjet);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($carrouselObjet);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'objet carrousel <strong>{$carrouselObjet->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_carrousel_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'objet carrousel n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/carrousel/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'éditer un objet du carrousel
     * 
     * @Route("/admin/carrousel/{id}/edit", name="admin_carrousel_edit")
     * 
     */
    public function edit(CarrouselObjet $carrouselObjet, EntityManagerInterface $manager, Request $request)
    {   
        
        $form = $this->createForm(CarrouselObjetType::class, $carrouselObjet);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($carrouselObjet);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "L'objet du carrousel <strong>{$carrouselObjet->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_carrousel_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'objet du carrousel n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/carrousel/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un objet du carousel
     * 
     * @Route("/admin/carrousel/{id}/delete", name="admin_carrousel_delete")
     *
     */
    public function delete(CarrouselObjet $carrouselObjet, EntityManagerInterface $manager) {

        $manager->remove($carrouselObjet);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'objet lié au carrousel <strong>{$carrouselObjet->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_carrousel_index");
    }

}
