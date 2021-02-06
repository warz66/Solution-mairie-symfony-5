<?php

namespace App\EventListener;

use App\Entity\Galerie;
use App\Entity\Publication;
use App\Entity\KiosqueObjet;
use App\Entity\CarrouselObjet;
use Vich\UploaderBundle\Event\Event;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class VichRemoveCacheListener
{
    private $cacheManager;
    private $filesystem;
    private $parameterBag;

    public function __construct(CacheManager $cacheManager, Filesystem $filesystem, ParameterBagInterface $parameterBag) {       
        $this->cacheManager = $cacheManager;
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
    }

    public function onVichUploaderPreRemove(Event $event)
    {   
        $object = $event->getObject();
        //$mapping = $event->getMapping();
        if ($object instanceof Galerie) { // à tester
            $this->cacheManager->remove($this->parameterBag->get('galerie_cover_path') . $object->getcoverImage());
        }
        if ($object instanceof Publication) { // à tester
            $this->cacheManager->remove($this->parameterBag->get('publication_cover_path') . $object->getcoverImage());
        }
        if ($object instanceof CarrouselObjet) { // à tester
            $this->cacheManager->remove($this->parameterBag->get('carrousel_cover_path') . $object->getcoverImage());
        }
        if ($object instanceof KiosqueObjet) { // à tester
            $this->cacheManager->remove($this->parameterBag->get('kiosque_thumbnails_path') . $object->getUrlThumbnail());
            $this->filesystem->remove($this->parameterBag->get('kernel.project_dir').'/public' . $this->parameterBag->get('kiosque_thumbnails_path') . $object->getUrlThumbnail());
        }
    }

}