<?php

namespace App\Controller;

use App\Form\FlashInfoType;
use App\Entity\FlashInfoObjet;
use App\Form\FlashInfoObjetType;
use App\Repository\FlashInfoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FlashInfoObjetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFlashInfoController extends AbstractController
{
    /**
     * @Route("/admin/flash-info", name="admin_flash-info_index")
     */
    public function index(FlashInfoRepository $repoFlashInfo, FlashInfoObjetRepository $repoFlashInfoObjet, Request $request, EntityManagerInterface $manager) {

        $flashInfo = $repoFlashInfo->findBy([], ['id'=>'ASC'], 1, 0);
        $formFlashInfo = $this->createForm(FlashInfoType::class, $flashInfo[0]);
        $formFlashInfo->handleRequest($request);
        
        if ($formFlashInfo->isSubmitted()) {
            if ($formFlashInfo->isValid()) {

                $manager->persist($flashInfo[0]);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "Le fil de flash d'informations a bien été modifié !"
                );
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, le fil de flash d'informations comporte des erreurs !"
                );
            }
        }

        return $this->render('admin/flash-info/index.html.twig', [
            'formFlashInfo' => $formFlashInfo->createView(),
            'flashInfoObjets' => $repoFlashInfoObjet->findBy([],['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/admin/flash-info/new", name="admin_flash-info_new")
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {   
        $flashInfoObjet = new FlashInfoObjet();
        $form = $this->createForm(FlashInfoObjetType::class, $flashInfoObjet);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($flashInfoObjet);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'objet lié au fil de flash d'informations <strong>{$flashInfoObjet->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_flash-info_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'objet lié au fil de flash d'informations n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/flash-info/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/flash-info/{id}/edit", name="admin_flash-info_edit")
     * 
     */
    public function edit(FlashInfoObjet $flashInfoObjet, EntityManagerInterface $manager, Request $request)
    {   
        
        $form = $this->createForm(FlashInfoObjetType::class, $flashInfoObjet);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($flashInfoObjet);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "L'objet lié au fil de flash d'informations <strong>{$flashInfoObjet->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_flash-info_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'objet lié au fil de flash d'informations n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/flash-info/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/flash-info/{id}/delete", name="admin_flash-info_delete")
     */
    public function delete(FlashInfoObjet $flashInfoObjet, EntityManagerInterface $manager) {

        $manager->remove($flashInfoObjet);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'objet lié au fil de flash d'informations <strong>{$flashInfoObjet->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_flash-info_index");
    }

}
