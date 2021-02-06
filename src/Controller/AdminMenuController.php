<?php

namespace App\Controller;

use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMenuController extends AbstractController
{
    /**
     * @Route("/admin/menu", name="admin_menu_edit")
     */
    public function edit(MenuRepository $repo, Request $request, EntityManagerInterface $manager)
    {   
        $menu = $repo->findBy([], ['id'=>'ASC'], 1, 0);
        $form = $this->createForm(MenuType::class, $menu[0]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($menu[0]);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "Le menu a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_menu_edit'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention le menu n'a pu être sauvegardée, veuillez vérifier s'il y a des erreurs !"
                );
            }
        }

        return $this->render('admin/menu/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
