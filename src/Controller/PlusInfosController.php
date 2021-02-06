<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlusInfosController extends AbstractController
{
    /**
     * @Route("/plus-infos", name="plus_infos")
     */
    public function index()
    {
        return $this->render('plus_infos.html.twig', [
        ]);
    }
}
