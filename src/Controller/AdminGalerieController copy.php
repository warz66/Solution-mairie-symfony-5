<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Galerie;
use App\Form\GalerieType;
use App\Repository\GalerieRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminGalerieController extends AbstractController
{
    /**
     * @Route("/admin/galerie", name="admin_galerie_index")
     */
    public function index(GalerieRepository $repo)
    {   

        return $this->render('admin/galerie/index.html.twig', [
            'galeries' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/galerie/{id}/{page<\d+>?1}/edit", name="admin_galerie_edit")
     */
    public function edit(Galerie $galerie, Request $request, EntityManagerInterface $manager, $page, PaginatorInterface $paginator, ImageRepository $repo)
    {   
        
        // on crée la pagination
        $nbImgPage = 30;
        if ($galerie->getOrderBy()) { $order = 'ASC'; } else { $order = 'DESC'; }
        $images = $galerie->getImagesOrderBy($order);
        $pagination = $paginator->paginate($images, $request->query->getInt('page',$page), $nbImgPage);
        /*$pagination->setCustomParameters([
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
            'size' => 'normal', # small|large (for template: twitter_bootstrap_v4_pagination)
            'style' => 'bottom',
            'span_class' => 'whatever',
        ]); */

        if ($page>1) { // si on enregistre et que la page est au dessus de 1 ce if n'est pas prit en compte, comprend pas ce que fait symfony.
            return $this->redirectToRoute('admin_galerie_edit', ['id' => $galerie->getId(), 'page' => '1']);    
        }

        // on vérifie si on sort de la route
        /*if ((ceil($pagination->getTotalItemCount()/$nbImgPage)<$page) and $page > 1) {
            return $this->redirectToRoute('admin_galerie_edit', ['id' => $galerie->getId(), 'page' => '1']);
        }*/

        /*$oldImgCaption = array();
        $originalImages = $images->getValues();
        foreach ($originalImages as $key => $image) {
            $oldImgCaption[$key]['caption'] = $image->getCaption();
            $oldImgCaption[$key]['id'] = $image->getId();
        }*/
        
        $form = $this->createForm(GalerieType::class, $galerie,[ "pagination" => $pagination ]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            
            $galerieRequest=$request->request->get('galerie');
            if(isset($galerieRequest['images'])){
                $imageRequest=$galerieRequest['images'];
                dump($imageRequest);
                foreach($imageRequest as $image) {
                    //$imageOrigin = $repo->findOneBy(['id' => $image['id']]);
                    if($image['remove'] === '1') {
                        $imageOrigin = $repo->findOneBy(['id' => $image['id']]);
                        $manager->remove($imageOrigin);
                    } else {
                        if ($image['captionOrigin'] != $image['caption']) {
                            $imageOrigin = $repo->findOneBy(['id' => $image['id']]);
                            $imageOrigin->setCaption($image['caption']);
                        }
                    }
                }
            }
            /*$caption=$request->request->get('updateCaption');
            $captionArray=array();
            if(isset($caption)) {
                for($i=0;$i<count($caption);$i++) {
                    $captionArray[]=explode("-",$caption[$i],2);
                    $image = $repo->findOneBy(['id' => $captionArray[$i][0]]);
                    $image->setCaption($captionArray[$i][1]);
                }
            }
            dump($captionArray);*/
            /*$intervalMin = $request->request->get('firstImg');
            $intervalMax = $request->request->get('lastImg');
            $imagesRequest = $request->request->get($form->getName());
            if(isset($imagesRequest['images'])) {
                $imagesRemain = $imagesRequest['images'];
                for( $i=$intervalMin; $i<=$intervalMax; $i++ ) {
                    if (false === (array_key_exists($i, $originalImages) and array_key_exists(strval($i), $imagesRemain))) {
                        if (isset($originalImages[$i])) {
                            $manager->remove($originalImages[$i]);
                        }
                    } else if (strcmp($imagesRemain[$i]['caption'], $oldImgCaption[$i]['caption'])!==0) {
                        $originalImages[$i]->setCaption($imagesRemain[$i]['caption']);
                    }  
                }
            } else {
                foreach ($originalImages as $image) { // cas où l'on supprime toutes les images sur une page
                    $manager->remove($image);
                }
            }*/
            /*$manager->flush();*/
            // on compare les modification avant et aprés submit, puis on applique les modifications
            /*$imagesRequest = $request->request->get($form->getName());
            if(isset($imagesRequest['images'])) { // si n'existe pas alors on supprime toute les images 
                $imagesRemain = $imagesRequest['images'];
                for( $i=array_key_first($originalImages); $i<(count($originalImages)+(array_key_first($originalImages)-1)); $i++ ) {
                        if (false === (array_key_exists($i, $originalImages) and array_key_exists(strval($i), $imagesRemain))) {
                            $manager->remove($originalImages[$i]);
                            //$manager->flush();
                        }
                    }
            } else {
                foreach ($originalImages as $image) { // cas où l'on supprime toutes les images sur une page
                    $manager->remove($image);
                    //$manager->flush();
                }
            }
            $manager->flush();*/

            //on enregistre sur le server les images aprés le post persist
            if(!empty($_FILES['uploadFile']['tmp_name'][0])) {
                $errorFile='';
                dump($_FILES['uploadFile']);
                for($i = 0; $i < count($_FILES['uploadFile']['tmp_name']); ++$i) {
                    if(is_uploaded_file($_FILES['uploadFile']['tmp_name'][$i]) && $_FILES['uploadFile']['size'][$i] < 500000 && $_FILES['uploadFile']['error'][$i] === 0 && ($_FILES['uploadFile']['type'][$i] === 'image/jpeg' || $_FILES['uploadFile']['type'][$i] === 'image/png')) {
                        $source_path = $_FILES['uploadFile']['tmp_name'][$i];
                        $file = uniqid() . '_' . $_FILES['uploadFile']['name'][$i];
                        /*$target_path = '..\public\img\indatabase\galerie\content\\' . $file;*/
                        $img_path = $this->getParameter('galerie_content_path') . $file;
                        $img = new Image;
                        $img->setSource_path($source_path);
                        $img->setUrl($img_path);
                        $img->setGalerie($galerie);
                        /*if($manager->persist($img)) { // trouver une meilleure solution, voir du coté PostPersist en enregistrant le source_path sans le mettre en bdd
                            move_uploaded_file($source_path, $target_path);
                        }*/
                        $manager->persist($img);
                        $manager->flush($img);
                        /*move_uploaded_file($source_path, $target_path);*/
                    } else {
                        unlink($_FILES['uploadFile']['tmp_name'][$i]); // a tester
                        $errorFile = $errorFile . $_FILES['uploadFile']['name'][$i].', ';
                    }
                }
            }

            //$manager->persist($galerie);
            $manager->flush();

            if (empty($errorFile)) {
                $this->addFlash('success', "La galerie <strong>{$galerie->getTitle()}</strong> a bien été modifiée !");
            } else {
                $this->addFlash('warning', "La galerie <strong>{$galerie->getTitle()}</strong> a bien été modifiée !<br>
                Cependant la ou les images <strong>{$errorFile}</strong> n'ont pas pu être enregistrées");
            }

            return $this->redirectToRoute('admin_galerie_edit', ['id' => $galerie->getId()]);                                              
        }

        return $this->render('admin/galerie/edit.html.twig', [
            'form' => $form->createView(), // attention si pas de redirection form plus le meme
            'galerieId' => $galerie->getId(),
            'pageMax' => ceil(count($galerie->getImages()->getValues())/$nbImgPage),
        ]);
    }

    /**
     * @Route("/admin/galerie/{id}/{page<\d+>?1}/edit/next", name="admin_galerie_next")
     */
    public function next(Galerie $galerie, Request $request, $page, PaginatorInterface $paginator)
    {   
        
        $nbImgPage = 30;
        if ($galerie->getOrderBy()) { $order = 'ASC'; } else { $order = 'DESC'; }
        $pagination = $paginator->paginate($galerie->getImagesOrderBy($order), $request->query->getInt('page',$page), $nbImgPage);

        $form = $this->createForm(GalerieType::class, $galerie,[ "pagination" => $pagination ]);

        return $this->render('admin/galerie/edit.html.twig', [
            'form' => $form->createView(),
            'galerieId' => $galerie->getId(),
            'pageMax' => ceil(count($galerie->getImages()->getValues())/$nbImgPage),
        ]);
    }
}
