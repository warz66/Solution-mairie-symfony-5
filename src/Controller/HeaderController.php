<?php

namespace App\Controller;

use App\Repository\InformationRepository;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HeaderController extends AbstractController
{
    public function header(InformationRepository $repo) {

        $information = $repo->findOneByNom('information');
        
        return $this->render('partials/header.html.twig', [
            'information' => $information,
        ]);
    }
}
