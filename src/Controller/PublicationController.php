<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Repository\GalerieRepository;
use App\Service\LiensUtilesService;
use App\Repository\PublicationRepository;
use App\Service\ElasticsearchCrudService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublicationController extends AbstractController
{   

    private $liensUtilesService;

    public function __construct(LiensUtilesService $liensUtilesService)
    {
        $this->liensUtilesService = $liensUtilesService;
    }

    /**
     * @Route("/publication/{slug}", name="publication_router")
     */
    public function publication(Publication $publication)
    {   

        if(($publication->getCategory()->getNom() === 'rubrique' || $publication->getCategory()->getNom() === 'sous-rubrique')  && !$publication->getTrash() ) {
            $response = $this->forward('App\Controller\PublicationController::rubrique', [
                'rubrique'  => $publication,
            ]);
            return $response;
            /*$response = $this->rubrique($publication);
            return $response;*/
        }

        if ($publication->getCategory()->getNom() === 'page' && $publication->getStatut() && !$publication->getTrash()) {
            $response = $this->forward('App\Controller\PublicationController::page', [
                'page'  => $publication,
            ]);
            return $response;
        }

        if ($publication->getCategory()->getNom() === 'actualite' && $publication->getStatut() && !$publication->getTrash()) {
            $response = $this->forward('App\Controller\PublicationController::actualite', [
                'actualite'  => $publication,
            ]);
            return $response;
        }

        if ($publication->getCategory()->getNom() === 'evenement' && $publication->getStatut() && !$publication->getTrash()) {
            $response = $this->forward('App\Controller\PublicationController::evenement', [
                'evenement'  => $publication,
            ]);
            return $response;
        }

        /* return redirect 404 */
        throw $this->createNotFoundException('Cette page n\'existe pas.');
    }

    /**
     * @Route("/recherche", name="recherche")
     */
    public function recherche(Request $request,/* ElasticsearchCrudService $esclient,*/ PublicationRepository $repo, GalerieRepository $galerieRepo)
    {   

        if($request->isMethod('GET')) {
            $resultat1 = $repo->fullTextActualite($request->query->get('recherche'));
            $resultat2 = $repo->fullTextEvenement($request->query->get('recherche'));
            $resultat3 = $repo->fullTextPage($request->query->get('recherche'));
            $resultat4 = $repo->fullTextRubriques($request->query->get('recherche'));
            $resultat5 = $galerieRepo->fullTextGalerie($request->query->get('recherche'));
            $resultats = array_merge($resultat1, $resultat2, $resultat3, $resultat4, $resultat5);
            usort($resultats, function($b, $a) {
                return $a['score'] <=> $b['score'];
            });
        }
        $resultats = array_slice($resultats,0,30);

        $publications = array();
        foreach($resultats as $publication) {
            $publications[] = $publication[0];

        }
        //$esResult = $esclient->search($request->query->get('recherche'));

        return $this->render('publication/recherche.html.twig', [
            'recherche' => $request->query->get('recherche'),
            'publications' => $publications,
            'resultats' => $resultats,
        ]);    
    }

    /**
     * @Route("/actualites/{page<\d+>?1}", name="actualites")
     */
    public function actualites($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request)
    {   
        // on crée la pagination
        $nbNews = 15;
        $data = $repo->findAllActualites();
        $news = $paginator->paginate($data, $request->query->getInt('page',$page), $nbNews);
        $news->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('publication/actualites.html.twig', [
            'news' => $news
        ]);
    }

    /**
     * @Route("/evenements/{page<\d+>?1}", name="evenements")
     */
    public function evenements($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request)
    {   
        // on crée la pagination
        $nbEvents = 15;
        $data = $repo->findAllEvenements();
        $events = $paginator->paginate($data, $request->query->getInt('page',$page), $nbEvents);
        $events->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('publication/evenements.html.twig', [
            'pastExist' => $repo->findAllEvenementsPassesExiste(),
            'past' => false,
            'events' => $events
        ]);
    }

    /**
     * @Route("/evenements-passés/{page<\d+>?1}", name="evenements_passes")
     */
    public function evenementsPasses($page, PublicationRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        // on crée la pagination
        $nbEvents = 15;
        $data = $repo->findAllEvenementsPasses();
        $events = $paginator->paginate($data, $request->query->getInt('page',$page), $nbEvents);
        $events->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('publication/evenements.html.twig', [
            'pastExist' => $repo->findAllEvenementsPassesExiste(),
            'past' => true,
            'events' => $events
        ]); 
        
    }

    public function actualite(Publication $actualite, $preview = null) 
    {   
        // on créer les liens utiles
        $liensUtiles = $this->liensUtilesService->make($actualite);

        return $this->render('publication/actualite.html.twig', [
            'liensUtiles' => $liensUtiles,
            'actualite' => $actualite,
            'preview' => $preview
        ]);
    }

    public function evenement(Publication $evenement, PublicationRepository $repo, $preview = null) 
    {   
        // on créer les liens utiles
        $liensUtiles = $this->liensUtilesService->make($evenement);

        return $this->render('publication/evenement.html.twig', [
            'liensUtiles' => $liensUtiles,
            'evenement' => $evenement,
            'preview' => $preview,
            'past' => $repo->findOneEvenementsPassesExiste($evenement->getId())
        ]);
    }

    // function qui retourne une partie du fil d'ariane
    private function makeFilAriane(Publication $publication){
        
        $filAriane = array();
        $filAriane[0] = $publication;
        while (!empty($publication->getParent())){
            array_unshift($filAriane,$publication->getParent());
            $publication = $publication->getParent();
        }
        return $filAriane;
    }

    public function rubrique(Publication $rubrique, PublicationRepository $repo) 
    {   
        // on créer le fil d'ariane
        $filAriane = $this->makeFilAriane($rubrique);

        // on gere le statut de publication des pages.
        $publications = $rubrique->getEnfants();
        foreach($publications->getValues() as $key => $publication) {
            if(!$publication->getStatut() || $publication->getTrash()) {
                $publications->remove($key);
            }
        }

        $enLien = array_merge($repo->findByCategoryEvent($rubrique->getRubrique()->getCategory()),$repo->findByCategoryActu($rubrique->getRubrique()->getCategory()));
        usort($enLien, function ($b, $a) {
            if ($a->getCategory()->getNom() === 'actualite') {
                $a = $a->getActualite()->getDebutPublication()->getTimestamp();
            } else {
                //$a = $a->getEvenement()->getDebutEvenement()->getTimestamp();
                $a = $a->getCreateAt()->getTimestamp();
            }
            if ($b->getCategory()->getNom() === 'actualite') {
                $b = $b->getActualite()->getDebutPublication()->getTimestamp();
            } else {
                //$b = $b->getEvenement()->getDebutEvenement()->getTimestamp();
                $b = $b->getCreateAt()->getTimestamp();
            }
            return $a <=> $b;
            //return $a->getCreateAt()->getTimestamp() <=> $b->getCreateAt()->getTimestamp();
        });
        $enLien = array_slice($enLien,0,12);

        // on créer les liens utiles
        $liensUtiles = $this->liensUtilesService->make($rubrique);

        return $this->render('publication/rubrique.html.twig', [
            'enLien' => $enLien,
            'filAriane' => $filAriane,
            'liensUtiles' => $liensUtiles,
            'publications' => $publications,
            'rubrique' => $rubrique
            ]);
    }

    public function page(Publication $page, $preview = null) 
    {   

        // on créer le fil d'ariane
        $filAriane = $this->makeFilAriane($page);

        // on créer les liens utiles
        $liensUtiles = $this->liensUtilesService->make($page);

        return $this->render('publication/page.html.twig', [
            'filAriane' => $filAriane,
            'liensUtiles' => $liensUtiles,
            'preview' => $preview,
            'page' => $page
        ]);
    }
}
