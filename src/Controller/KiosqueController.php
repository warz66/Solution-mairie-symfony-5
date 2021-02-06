<?php

namespace App\Controller;

use App\Repository\KiosqueObjetRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class KiosqueController extends AbstractController
{
    /**
     * @Route("/kiosque/{page<\d+>?1}", name="kiosque")
     */
    public function kiosque($page, KiosqueObjetRepository $repo, Request $request, PaginatorInterface $paginator)
    {   

        // on crée la pagination
        $nb = 25;
        $data = $repo->findAllStatutOk();
        $kiosqueObjets = $paginator->paginate($data, $request->query->getInt('page',$page), $nb);
   
        return $this->render('kiosque/kiosque.html.twig', [
            'kiosqueObjets' => $kiosqueObjets,
            'pageMax' => ceil(count($data)/$nb),
        ]);
    }

    /**
    * Permet d'envoyer la page suivante d'une galerie via l'appel ajax d'infinite scroll
    * 
    * @Route("/kiosque/{page<\d+>?1}/next", name="kiosque_next")
    */
    public function next(KiosqueObjetRepository $repo, Request $request, $page, PaginatorInterface $paginator)
    {   
        $nb = 25;
        $data = $repo->findAllStatutOk();
        $kiosqueObjets = $paginator->paginate($data, $request->query->getInt('page',$page), $nb);

        return $this->render('kiosque/kiosque.html.twig', [
            'kiosqueObjets' => $kiosqueObjets,
            'pageMax' => ceil(count($data)/$nb),
        ]);
    }
}
