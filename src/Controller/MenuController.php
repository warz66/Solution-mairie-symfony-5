<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{

    public function mainMenu(MenuRepository $menuRepo, PublicationRepository $pubRepo)
    {  
        $menu = $menuRepo->findBy([], ['id'=>'ASC'], 1, 0)[0]->getAllRubriques();
        //$realMenu = $this->_addChilds($menu, $pubRepo);

        // menu sans arborescence sous array, cependant inutile car on peut refaire dans twig
        /*$rubriques = $menuRepo->findBy([], ['id'=>'ASC'], 1, 0)[0]->getAllRubriques();
        foreach($rubriques as $key => $rubrique) {
            if (null !== $rubrique) {
                $enfantsRubrique = $pubRepo->find($rubrique->getId())->getEnfants()->getValues();
                $menu[$key]['title'] = $rubrique->getTitle();
                $menu[$key]['slug'] = $rubrique->getSlug();
                //$menu[$key]['statut'] = $rubrique->getStatut();
                $menu[$key]['enfants'] = $enfantsRubrique;
            } else {
                $menu[$key] = null;
            }
        }*/

        return $this->render('partials/_menu.html.twig', [
            'menu' => $menu
        ]);
    }

    /**
     * function recursive pour creer un tableau qui représente le menu avec une arboresecence , cependant inutile car on peut tout faire dans twig 
     *
     * @param [type] $level
     * @param PublicationRepository $repo
     * @return void
     */
    private function _addChilds($level, PublicationRepository $repo) {
        foreach ($level as $key => $publication) {
            if (null !== $publication) {
                $enfants = $repo->find($publication->getId())->getEnfants()->getValues();
                $menu[$key]['title'] = $publication->getTitle();
                $menu[$key]['slug'] = $publication->getSlug();
                $menu[$key]['statut'] = $publication->getStatut();
                if(empty($enfants)) {
                    $menu[$key]['enfants'] = null;
                } else {
                    $menu[$key]['enfants'] = $this->_addChilds($enfants, $repo);
                }
            } else {
                $menu[$key] = null;
            }
        }
        return $menu;
    }

}
