<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlanSiteController extends AbstractController
{
    /**
     * @Route("/plan-du-site", name="plan_site")
     */
    public function index()
    {
        return $this->render('plan_site.html.twig', [
        ]);
    }
}
