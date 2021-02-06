<?php

namespace App\EventListener;


use App\Entity\Image;
use App\Repository\ImageRepository;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class DeleteFileListener
{

    private $repo;
    private $cacheManager;

    public function __construct(ImageRepository $repo, CacheManager $cacheManager) {       
        $this->repo = $repo;
        $this->cacheManager = $cacheManager;
    }

    
    public function postRemove(Image $image)
    {   

        if (is_null($image->getPublication())) {  // Supprime l'image si elle n'est pas lié a une publication, cela veut dire qu'elle provient d'une galerie ou autre ? 
            $current_dir_path = getcwd();
            $current_dir_file = $current_dir_path.$image->getUrl();
            if (is_file($current_dir_file)) {
                unlink($current_dir_file);
                $this->cacheManager->remove($image->getUrl()); // on supprime l'image du cache de liip imagine 
            }
        } else { // Permet de supprimer le fichier lié à l'image si il n'est plus lié a aucune autre publication pendant l'evenement postRemove
            $reponse = $this->repo->findSameImageInOtherPublication($image->getPublication(), $image->getUrl());
            if (is_null($reponse)) {
                $current_dir_path = getcwd();
                $current_dir_file = $current_dir_path.$image->getUrl();
                if (is_file($current_dir_file)) {
                    unlink($current_dir_file);
                }
            }
        }
    }

}