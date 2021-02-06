<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PublicationController extends AbstractController
{
    /**
     * @Route("/{rubrique}")
     * @ParamConverter("rubrique", options={"mapping": {"rubrique":"slug"}})
     */
    public function publicationRubrique(Publication $rubrique)
    {
        // si la rubrique correspond bien à ça category
         if($rubrique->getCategory()->getNom() !== 'rubrique') {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }

        dump($rubrique->getEnfants()->getValues());

        return $this->render('publication/rubrique.html.twig', [
        ]);
    }

    /**
     * @Route("/{rubrique}/{page}")
     * @ParamConverter("rubrique", options={"mapping": {"rubrique":"slug"}})
     * @ParamConverter("page", options={"mapping": {"page":"slug"}})
     */
    public function publicationPage1(Publication $rubrique, Publication $page)
    {

        // si la page est publié.
        if (!$page->getStatut())  { 
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // si les pages correspondent bien à leur category respective
        if($page->getCategory()->getNom() !== 'page' || $rubrique->getCategory()->getNom() !== 'rubrique') {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // si l'arborescence n'est pas interrompu
        if (empty($page->getParent())) {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // si les affiliations parents enfants sont bien respectées
        if($page->getParent()->getSlug() !== $rubrique->getSlug()) {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // savoir si la rubrique est bien dans le menu ? 

        return $this->render('publication/page.html.twig', [
        ]);
    }

    /**
     * @Route("/{rubrique}/{sousrubrique}/{page}")
     * @ParamConverter("rubrique", options={"mapping": {"rubrique":"slug"}})
     * @ParamConverter("sousrubrique", options={"mapping": {"sousrubrique":"slug"}})
     * @ParamConverter("page", options={"mapping": {"page":"slug"}})
     */
    public function publicationPage2(Publication $rubrique,Publication $sousrubrique, Publication $page)
    {   

        /* on test si la route est valide */

        // si la page est publié.
        if (!$page->getStatut())  { 
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // si les pages correspondent bien à leur category respective
        if($page->getCategory()->getNom() !== 'page' || $sousrubrique->getCategory()->getNom() !== 'sous-rubrique' || $rubrique->getCategory()->getNom() !== 'rubrique') {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // si l'arborescence n'est pas interrompu
        if (empty($page->getParent()) || empty($sousrubrique->getParent())) {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // si les affiliations parents enfants sont bien respectées
        if($page->getParent()->getSlug() !== $sousrubrique->getSlug() || $sousrubrique->getParent()->getSlug() !== $rubrique->getSlug()) {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }
        // savoir si la rubrique est bien dans le menu ? 

        return $this->render('publication/page.html.twig', [
        ]);
    }
}
