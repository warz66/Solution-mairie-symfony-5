<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Service\ImgToPublicationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPublicationController extends AbstractController
{
    /**
     * Permet d'éditer une publication
     * 
     * @Route("/admin/publication/{id}/edit", name="admin_publication_edit")
     * 
     */
    public function index(Publication $publication, EntityManagerInterface $manager, Request $request, ImgToPublicationService $imgToPublication)
    {   

        /*if(!empty($_FILES)) {
            for($i = 0; $i < count($_FILES['uploadFile']); ++$i) {
                if(is_uploaded_file($_FILES['uploadFile']['tmp_name'][$i])) {
                    $source_path = $_FILES['uploadFile']['tmp_name'][$i];
                    $target_path = 'C:\laragon\www\Mairie\public\uploads\test\\' . $_FILES['uploadFile']['name'][$i];
                    $img_path = '\uploads\test\\' . $_FILES['uploadFile']['name'][$i];
                    if(move_uploaded_file($source_path, $target_path)) {
                        echo '<img src="'.$img_path.'" class="img-thumbnail" width="300" height="250" />';
                    }
                }
            }
        }*/

        
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
      
            $imgToPublication->imgPublicationToBdd($publication); // va gérer les images du content de la publication dans la bdd

            $manager->persist($publication);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "La publication <strong>{$publication->getTitle()}</strong> a bien été modifiée !"
            );
        }

        return $this->render('admin/publication/edit.html.twig', [
            'form' => $form->createView(),
            'publication' => $publication,
        ]);
    }

    /**
     * Permet de supprimer une publication
     * 
     * @Route("/admin/publication/{id}/delete", name="admin_publication_delete")
     *
     */
    public function delete(Publication $publication, EntityManagerInterface $manager) {

            $manager->remove($publication);
            $manager->flush();

            /*$this->addFlash(
                'success',
                "La publication <strong>{$publication->getTitle()}</strong> a bien été supprimée !"
            );*/
            
        return $this->redirectToRoute('homepage');
    }

    /**
     * 
     * @Route("/admin/publication/{id}/upload", name="admin_publication_upload")
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function upload() : Response {

        if(!empty($_FILES)) {
            for($i = 0; $i < count($_FILES['uploadFile']['tmp_name']); ++$i) {
                if(is_uploaded_file($_FILES['uploadFile']['tmp_name'][$i])) {
                    $source_path = $_FILES['uploadFile']['tmp_name'][$i];
                    $target_path = '..\public\uploads\test\\' . $_FILES['uploadFile']['name'][$i];
                    $img_path = '\uploads\test\\' . $_FILES['uploadFile']['name'][$i];
                    if(move_uploaded_file($source_path, $target_path)) {
                        $output[$i] = $img_path;
                    }
                }
            }
        }
        return $this->json($output);
    }

}
