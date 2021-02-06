<?php

namespace App\EventListener;

use App\Entity\Publication;
use App\Service\ElasticsearchCrudService;

class ElasticsearchCrudListener
{
    private $esclient;
    private $id;

    public function __construct(ElasticsearchCrudService $esclient) {       
        $this->esclient = $esclient;
    }

    public function preRemove(Publication $publication) {
        $this->id = $publication->getId();
    }

    public function postRemove() {   
        $this->esclient->deletePublication($this->id);
    }

    public function postUpdate(Publication $publication) {
        $this->esclient->updatePublication($publication);
    }

    public function postPersist(Publication $publication) {
        $this->esclient->indexPublication($publication);
    }
}