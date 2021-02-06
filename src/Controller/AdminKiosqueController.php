<?php

namespace App\Controller;

use ImalH\PDFLib\PDFLib;
use Spatie\PdfToImage\Pdf;
use App\Entity\KiosqueObjet;
use App\Form\KiosqueObjetType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\KiosqueObjetRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminKiosqueController extends AbstractController
{
    /**
     * Index des objets du kiosque
     * 
     * @Route("/admin/kiosque/{page<\d+>?1}", name="admin_kiosque_index")
     */
    public function index($page, KiosqueObjetRepository $repo, PaginatorInterface $paginator, Request $request)
    {   
        // on crée la pagination
        $nbPage = 20;
        $data = $repo->findby([],['id'=>'DESC']); // find par date de parution du plus récent au plus ancien
        $kiosqueObjets = $paginator->paginate($data, $request->query->getInt('page',$page), $nbPage);
        $kiosqueObjets->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/kiosque/index.html.twig', [
            'kiosqueObjets' => $kiosqueObjets,    
        ]);
    }

    /**
     * Permet d'editer un objet du kiosque
     * 
     * @Route("/admin/kiosque/{id}/edit", name="admin_kiosque_edit")
     */
    public function edit(KiosqueObjet $revue, EntityManagerInterface $manager, Request $request)
    {   

        $form = $this->createForm(KiosqueObjetType::class, $revue);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($revue);
                $manager->flush();

                $pdfFile=$this->getParameter('kernel.project_dir').'/public'.$this->getParameter('kiosque_path').$revue->getUrl(); 
                $Outputpath=$this->getParameter('kernel.project_dir').'/public'.$this->getParameter('kiosque_thumbnails_path');
                $pdflib = (new PDFLib())
                        ->setPdfPath($pdfFile)
                        ->setOutputPath($Outputpath)
                        ->setImageFormat(PDFLib::$IMAGE_FORMAT_PNG)
                        ->setDPI(300)
                        ->setPageRange(1,1)
                        ->setFilePrefix($revue->getUrl().'_thumbnail')
                        ->convert();
                $revue->setUrlThumbnail($pdflib[0]);

                $manager->persist($revue);
                $manager->flush();
            
                $this->addFlash(
                    'success',
                    "La revue <strong>{$revue->getTitle()}</strong> a bien été modifiée !"
                );

                return $this->redirectToRoute('admin_kiosque_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la revue n'a pu être sauvegardée, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/kiosque/edit.html.twig', [   
            'form' => $form->createView(), 
        ]); 
    } 
    
    /**
     * Permet de creer un objet du kiosque
     * 
     * @Route("/admin/kiosque/new", name="admin_kiosque_new")
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {   

        $revue = new KiosqueObjet();
        $form = $this->createForm(KiosqueObjetType::class, $revue);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                $manager->persist($revue);

                $pdfFile=$this->getParameter('kernel.project_dir').'/public'.$this->getParameter('kiosque_path').$revue->getUrl(); 
                $Outputpath=$this->getParameter('kernel.project_dir').'/public'.$this->getParameter('kiosque_thumbnails_path');
                $pdflib = (new PDFLib())
                        ->setPdfPath($pdfFile)
                        ->setOutputPath($Outputpath)
                        ->setImageFormat(PDFLib::$IMAGE_FORMAT_PNG)
                        ->setDPI(300)
                        ->setPageRange(1,1)
                        ->setFilePrefix($revue->getUrl().'_thumbnail')
                        ->convert();
                
                $revue->setUrlThumbnail($pdflib[0]);
                $manager->persist($revue);
                $manager->flush();

                /*$pdflib->convert();
                dump($pdflib);*/
                /*$pdf = new Pdf($file);
                $pdf->saveImage($file2);*/
        
        
                /* = "gs -dNOPAUSE -dBATCH -r300 -dDownScaleFactor=3  -sDEVICE=png16m -sOutputFile=\"" .$file . "_%d.png\"  " . $file;
                $returnedvalue = exec($gscommand);
                dump($returnedvalue);*/
        
        
                /*exec('"C:\Program Files\ImageMagick-7.0.10-Q16-HDRI\magick" '.$path.'[0] '.$path2, $output, $return);
                dump($output);
                dump('"C:\Program Files\ImageMagick-7.0.10-Q16-HDRI\magick" '.$path.'[0] '.$path2);
                dump($return);*/
                //phpinfo();
                /*$path = $this->getParameter('kernel.project_dir').'/public'.$this->getParameter('ressources_path').$page->getRessources()->getValues()[0]->getUrl();
                $im = new \Imagick($path.'[0]');
                //$im->setIteratorIndex(0);
                $im->setCompression(\imagick::COMPRESSION_JPEG);
                //$im->setImageCompressionQuality(100);
                $im->thumbnailImage(300, 300, true, true);
                $im->writeImage($this->getParameter('kernel.project_dir').'/public'.$this->getParameter('ressources_path').'lool.jpg');
                $im->clear();
                $im->destroy();*/
        
                /*$image = new \Imagick();
                $image->newImage(1, 1, new \ImagickPixel('#ffffff'));
                $image->setImageFormat('png');
                $pngData = $image->getImagesBlob();
                dump(strpos($pngData, "\x89PNG\r\n\x1a\n") === 0 ? 'Ok' : 'Failed'); */

                $this->addFlash(
                    'success',
                    "La revue <strong>{$revue->getTitle()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_kiosque_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention la revue n'a pu être sauvegardée, veuillez vérifier s'il y a des message d'erreurs !"
                );
            }
        }

        return $this->render('admin/kiosque/new.html.twig', [  
            'form' => $form->createView(), 
        ]); 
    }
    
    /**
     * @Route("/admin/kiosque/{id}/delete", name="admin_kiosque_delete")
     */
    public function delete(KiosqueObjet $revue, EntityManagerInterface $manager) {

        $manager->remove($revue);
        $manager->flush();

        $this->addFlash(
            'success',
            "La revue <strong>{$revue->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_kiosque_index");
    }

    /**
     * @Route("/admin/kiosque/{id}/statut", name="admin_kiosque_statut")
     */
    public function statut(KiosqueObjet $revue, EntityManagerInterface $manager) {
        $statut=$_POST['statut'];
        if($statut=='true') {
            $revue->setStatut(true);
            $manager->flush();
            return $this->json(true); 
        } else {
            $revue->setStatut(false);
            $manager->flush();
            return $this->json(false);
        }
    }
}
