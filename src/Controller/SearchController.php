<?php

namespace App\Controller;

use App\Repository\PublicationRepository;
use App\Service\ElasticsearchCrudService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class SearchController extends AbstractController
{

    /**
     * @Route("/search", name="search_result")
     */
    public function search(Request $request, ElasticsearchCrudService $esclient, PublicationRepository $publicationRepo) {

        $form = $this->createFormBuilder(null, array(
            'attr' =>array(
                'class' => 'form-inline my-2 my-lg-0 mr-2'
            )
            ))->setAction($this->generateUrl('search_result'))
              ->add('search', SearchType::class, ['label' => false, 'attr' => ['placeholder' =>  'taper votre recherche']])
              ->getForm();
        
        $form->handleRequest($request);      

        if ( $form->isSubmitted() && $form->isValid() ) {

            $research = $form->getData();

            return $this->render('search/result.html.twig', [
                'research' => $esclient->search($research['search']),
                'research2' => $publicationRepo->fullText($research['search']),
                'form' => $form->createView()
            ]);
        } 

        return $this->render('partials/_form.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
