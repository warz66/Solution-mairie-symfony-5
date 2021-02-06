<?php

namespace App\EventListener;

use App\Entity\CarrouselObjet;
use Vich\UploaderBundle\Event\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class VichImgRemoveInDBCarrousel
{   
    private $manager;
    private $requestStack;


    public function __construct(EntityManagerInterface $manager, RequestStack $requestStack) {       
        $this->manager = $manager;
        $this->requestStack = $requestStack;
    }

    public function onVichUploaderPostRemove(Event $event)
    {   
        
        $request = $this->requestStack->getCurrentRequest();
        $object = $event->getObject();
        if ($object instanceof CarrouselObjet && $request->attributes->get('_route') == 'admin_carrousel_edit') { 
            $object->setCoverImage(null);
            $this->manager->persist($object);
            $this->manager->flush();
        }  
    }
}