<?php

namespace App\Controller;

use App\Form\InformationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminInformationController extends AbstractController
{
    /**
     * @Route("/admin/information", name="admin_information_edit")
     */
    public function index(InformationRepository $repo, EntityManagerInterface $manager, Request $request)
    {   
        $information = $repo->findOneByNom('information');

        $form = $this->createForm(InformationType::class, $information);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($information);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "Les informations ont bien été enregistrées."
                );
                
                return $this->redirectToRoute('admin_information_edit'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention les informations n'ont pu être sauvegardées, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/information/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
