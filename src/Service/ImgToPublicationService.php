<?php 

namespace App\Service;

use DOMDocument;
use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class de gestion des images lors de la création édition des publications
 * 
 */
class ImgToPublicationService {
    
    private $manager;
    private $repo;

    public function __construct(EntityManagerInterface $manager, ImageRepository $repo) {     
        // initialisations
        $this->manager = $manager;
        $this->repo    = $repo; 
    }

    /**
     * 1/ Fonction qui recupére une publication, extrait les urls de chaque images du content dans un tableau, 2/ puis va enregistrer les images dans la bdd si elle n'existe pas et modifier le caption d'une image existante si modifier, 3/ On supprime les images qui ne sont plus dans la publication
     * 
     */
    public function imgPublicationToBdd($publication) {
        
        $imgOnContent = array();
        $html = $publication->getContent();
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $image) { // on boucle sur chaque image du content de la publication

            $findSameImage = $this->repo->findSameImage($publication->getId(),$image->getAttribute('src')); // on regarde si l'image existe déjà
            $imgOnContent[]=$findSameImage;

            if (is_null($findSameImage)) { // si elle n'existe pas on l'enregistre dans la bdd 
                $objImg = new Image;
                $objImg->setUrl($image->getAttribute('src'));
                $objImg->setCaption($image->getAttribute('alt'));
                $publication->addImage($objImg);
                $this->manager->persist($objImg); // on persist l'objet maintenant car sinon on va creer des doublons dans la bdd

            } else if ($image->getAttribute('alt') != $findSameImage->getCaption()) { // on regarde si le caption de l'image à était modifier
                $findSameImage->setCaption($image->getAttribute('alt'));
            }
        }

        // on supprime les images qui ne sont plus dans le content d'une publication
        $imgOnPublication = $this->repo->findImageOnPublication($publication->getId());
        foreach ($imgOnPublication as $image) {
            if (!in_array($image,$imgOnContent)) {
                $this->manager->remove($image);
            }
        }
    }
}