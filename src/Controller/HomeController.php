<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\CarrouselRepository;
use App\Repository\FlashInfoRepository;
use App\Repository\AccesRapideRepository;
use App\Repository\KiosqueObjetRepository;
use App\Repository\PublicationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(MenuRepository $menuRepo, PublicationRepository $publication, CarrouselRepository $carrouselRepo, AccesRapideRepository $accesRapideRepo, FlashInfoRepository $flashInfoRepo, KiosqueObjetRepository $kiosqueRepo)
    {   

    // on construit le carrousel
        $carrouselObject = $carrouselRepo->findBy([], ['id'=>'ASC'], 1, 0)[0]->getAllEmplacement();
        if (isset($carrouselObject)){
            foreach($carrouselObject as $key => $item) {
                if ( null !== $item->getLienPublication() && $item->getLienPublication()->getStatut() && !$item->getLienPublication()->getTrash()) {
                    if (null !== $item->getTitle()) {
                        $carrousel[$key]['title'] = $item->getTitle();
                    } else {
                        $carrousel[$key]['title'] = $item->getLienPublication()->getTitle();
                    }
                    if (null !== $item->getIntroduction()) {
                        $carrousel[$key]['introduction'] = $item->getIntroduction();
                    } else {
                        $carrousel[$key]['introduction'] = $item->getLienPublication()->getIntroduction();
                    }
                    if (null !== $item->getCoverImage()) {
                        $carrousel[$key]['coverImage'] = $item->getCoverImage();
                    } else {
                        $carrousel[$key]['coverImagePublication'] = true;
                        $carrousel[$key]['coverImage'] = $item->getLienPublication()->getCoverImage();
                    }
                    $carrousel[$key]['lien'] = '/publication/'.$item->getLienPublication()->getSlug();
                    
                } else if((is_file($this->getParameter('kernel.project_dir').'/public'.$this->getParameter('carrousel_cover_path').$item->getCoverImage()) || strstr($item->getCoverImage(),'picsum'))){ // Résout une exception si un objet du carrousel ne contient pas du tout d'image réel, // à corriger lorsque picsum n'est plus utilisé 
                    $carrousel[$key]['title'] = $item->getTitle();
                    $carrousel[$key]['introduction'] = $item->getIntroduction();
                    $carrousel[$key]['coverImage'] = $item->getCoverImage();
                    $carrousel[$key]['lien'] = null;
                }
            }
        } else {
            $carrousel = null;
        }

    // menu
        $menu = $menuRepo->findBy([], ['id'=>'ASC'], 1, 0)[0]->getAllRubriques();

    // flash info
        $flashInfoItems = $flashInfoRepo->findBy([], ['id'=>'ASC'], 1, 0)[0]->getAllEmplacement();

    // acces rapide
        $accesRapideItems = $accesRapideRepo->findBy([], ['id'=>'ASC'], 1, 0)[0]->getAllEmplacement();
    
    // kiosque
        $kiosqueObjets = $kiosqueRepo->findAllStatutOk(12);     

        return $this->render('home.html.twig', [
            'menu' => $menu,
            'flashInfoItems' => $flashInfoItems,
            'accesRapideItems' => $accesRapideItems,
            'kiosqueObjets' => $kiosqueObjets,
            'carrousel' => $carrousel,
            'news' => $publication->findActualiteLimit(6),
            'events' => $publication->findEvenementLimit(6)
        ]);
    }

}
