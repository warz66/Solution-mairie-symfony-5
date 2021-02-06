<?php

namespace App\EventListener;


use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LienUtileInterneRepository;

class PublicationCascadeRemoveDependenciesLiensInternesListener
{

    private $repo;
    private $manager;

    public function __construct(LienUtileInterneRepository $repo, EntityManagerInterface $manager) {       
        $this->repo = $repo;
        $this->manager = $manager;
    }

    
    public function preRemove(Publication $publication)
    {   
        $liensInternes = $this->repo->findByLienPublication($publication->getId());
        foreach ($liensInternes as $lienInterne) {
            $this->manager->remove($lienInterne);
        }
        $this->manager->flush();
    }

}