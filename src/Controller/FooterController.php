<?php

namespace App\Controller;

use App\Repository\InformationRepository;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FooterController extends AbstractController
{

    public function footer(InformationRepository $repo) {

        $information = $repo->findOneByNom('information');
        
        return $this->render('partials/footer.html.twig', [
            'information' => $information,
        ]);
    }
}
