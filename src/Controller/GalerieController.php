<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Repository\GalerieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GalerieController extends AbstractController
{
    /**
     * @Route("/galeries/{page<\d+>?1}", name="galeries")
     */
    public function galeries($page, GalerieRepository $repo, Request $request, PaginatorInterface $paginator)
    {   
        // on crée la pagination
        $nbGaleries = 15;
        $data = $repo->findAllStatutOk();
        $galeries = $paginator->paginate($data, $request->query->getInt('page',$page), $nbGaleries);
        $galeries->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('galerie/galeries.html.twig', [
            'galeries' => $galeries,
        ]);
    }

    /**
     * @Route("/galerie/{slug}/{page<\d+>?1}", name="galerie")
     */
    public function galerie(Galerie $galerie, $page, PaginatorInterface $paginator, Request $request)
    {   
        if (!$galerie->getStatut() || $galerie->getTrash()) {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        } 

        // on crée la pagination
        $nbImgPage = 30;
        if ($galerie->getOrderBy()) { $order = 'ASC'; } else { $order = 'DESC'; }
        $data = $galerie->getImagesOrderBy($order);
        $images = $paginator->paginate($data, $request->query->getInt('page',$page), $nbImgPage);

        return $this->render('galerie/galerie.html.twig', [
            'galerie' => $galerie,
            'images' => $images,
            'pageMax' => ceil(count($galerie->getImages()->getValues())/$nbImgPage),
        ]);
    }

    /**
    * Permet d'envoyer la page suivante d'une galerie via l'appel ajax d'infinite scroll
    * 
    * @Route("/galerie/{slug}/{page<\d+>?1}/next", name="galerie_next")
    */
    public function next(Galerie $galerie, Request $request, $page, PaginatorInterface $paginator)
    {   
        $nbImgPage = 30;
        if ($galerie->getOrderBy()) { $order = 'ASC'; } else { $order = 'DESC'; }
        $images = $paginator->paginate($galerie->getImagesOrderBy($order), $request->query->getInt('page',$page), $nbImgPage);

        return $this->render('galerie/galerie.html.twig', [
            'galerie' => $galerie,
            'images' => $images,
            'pageMax' => ceil(count($galerie->getImages()->getValues())/$nbImgPage),
        ]);
    }
}
