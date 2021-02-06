<?php

namespace App\Controller;

use App\Form\MenuType;
use App\Form\RubriqueType;
use App\Entity\Publication;
use App\Form\RubriqueExtendType;
use App\Repository\MenuRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use App\Service\MessageDependenciesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminRubriqueController extends AbstractController
{
    /**
     * Index des rubriques
     * 
     * @Route("/admin/rubrique", name="admin_rubrique_index")
     *
     */
    public function index(MenuRepository $repoMenu, Request $request, EntityManagerInterface $manager, PublicationRepository $repoPublication, MessageDependenciesService $md) {

        $menu = $repoMenu->findBy([], ['id'=>'ASC'], 1, 0);
        $form = $this->createForm(MenuType::class, $menu[0]);
        $rubriques = $repoPublication->findCategory('rubrique', 0);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($menu[0]);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "Le menu a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_rubrique_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention le menu n'a pu être sauvegardée, veuillez vérifier s'il y a des erreurs !"
                );
            }
        }

        return $this->render('admin/rubrique/index.html.twig', [
            'form' => $form->createView(),
            'rubriques' => $rubriques,
            'rubriquesWithDependencies' => $md->getMsgRubriquesDependencies($rubriques),
        ]);
    }

    /**
     * @Route("/admin/rubrique/new", name="admin_rubrique_new")
     */
    public function new(EntityManagerInterface $manager, Request $request, CategoryRepository $CategoryRepo)
    {   
        $rubrique = new Publication();
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->add('rubrique', RubriqueExtendType::class, ['label' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $rubrique->setCreateAt(new \DateTime('now'))
                         ->setUpdatedAt($rubrique->getCreateAt())
                         ->setStatut(1)
                         ->setCategory($CategoryRepo->findOneBy(['nom' => 'rubrique']));
                $manager->persist($rubrique);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "La rubrique <strong>{$rubrique->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_rubrique_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la rubrique n'a pu être sauvegardée, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/rubrique/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'éditer une rubrique
     * 
     * @Route("/admin/rubrique/{id}/edit", name="admin_rubrique_edit")
     * 
     */
    public function edit(Publication $rubrique, EntityManagerInterface $manager, Request $request)
    {   
        
        if ($rubrique->getCategory()->getNom() !== 'rubrique') {
            throw $this->createNotFoundException("Cette rubrique n'existe pas");
        }
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->add('rubrique', RubriqueExtendType::class, ['label' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($rubrique);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "La rubrique <strong>{$rubrique->getTitle()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_rubrique_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la rubrique n'a pu être sauvegardée, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/rubrique/edit.html.twig', [
            'trash' => ($request->get('trash')) ? true : false,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Index des rubriques dans la corbeille
     * 
     * @Route("/admin/rubrique/trash", name="admin_rubrique_trash_index")
     *
     */
    public function trashIndex(PublicationRepository $repo) {

        return $this->render('admin/rubrique/trash.html.twig', [
            'rubriques' => $repo->findCategory('rubrique', 1)
        ]);
    }

    /**
     * Permet d'envoyer une rubrique à la corbeille
     * 
     * @Route("/admin/rubrique/{id}/trash", name="admin_rubrique_trash")
     *
     */
    public function trash(Publication $rubrique, EntityManagerInterface $manager, MenuRepository $repoMenu) {

        // lorsqu'on envoit une rubrique vers la corbeille alors qu'elle est dans le menu alors on met ce champ du menu respectif à null
        $menu = $repoMenu->findBy([], ['id'=>'ASC'], 1, 0)[0];
        $object = new \ReflectionClass($menu);
        foreach($object->getMethods() as $method) {
            $name = $method->getName();
            if(strpos($name, 'getRubrique') === 0) {
                if ($method->invoke($menu) === $rubrique) {
                    $chaine = explode('getRubrique', $name);
                    break;
                }
            }
        }
        if (isset($chaine[1])) {
            $methodName = 'setRubrique'.$chaine[1];
            $menu->$methodName(null);
            $manager->persist($menu); 
        }
        
        $rubrique->setTrash(1);
        $manager->persist($rubrique);
        $manager->flush();

        $this->addFlash(
            'success',
            "La rubrique <strong>{$rubrique->getTitle()}</strong> a bien été envoyée à la corbeille !"
        );

        return $this->redirectToRoute("admin_rubrique_index");
    }

    /**
     * Permet de restaurer une rubrique
     * 
     * @Route("/admin/rubrique/{id}/restore", name="admin_rubrique_restore")
     *
     */
    public function restore(Publication $rubrique, EntityManagerInterface $manager) {

        $rubrique->setTrash(0);
        $manager->persist($rubrique);
        $manager->flush();

        $this->addFlash(
            'success',
            "La rubrique <strong>{$rubrique->getTitle()}</strong> a bien été restaurée"
        );

        return $this->redirectToRoute("admin_rubrique_trash_index");
    }

    /**
     * Permet de supprimer une rubrique définitivement
     * 
     * @Route("/admin/rubrique/{id}/delete", name="admin_rubrique_delete")
     *
     */
    public function delete(Publication $rubrique, EntityManagerInterface $manager) {

        $manager->remove($rubrique);
        $manager->flush();

        $this->addFlash(
            'success',
            "La rubrique <strong>{$rubrique->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_rubrique_trash_index");
    }

    /**
     * Permet de vider la corbeille
     * 
     * @Route("/admin/rubrique/trash/empty", name="admin_rubrique_trash_empty")
     *
     */
    public function trashEmpty(EntityManagerInterface $manager, PublicationRepository $repo) {

        $rubriques = $repo->findCategory('rubrique', 1);

        foreach ($rubriques as $rubrique) {
            $manager->remove($rubrique);
            $manager->flush();
        }
        
        $this->addFlash(
            'success',
            "La corbeille a bien été vidée !"
        );

        return $this->redirectToRoute("admin_rubrique_trash_index");
    }

}
