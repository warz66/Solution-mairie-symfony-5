<?php

namespace App\Service;

use App\Entity\Publication;
use Elasticsearch\ClientBuilder;
use Doctrine\ORM\EntityManagerInterface;

class ElasticsearchCrudService
{
    private $esclient = null;
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->esclient = ClientBuilder::create()->build();
        $this->manager = $manager;
    }

    public function mapping() {

    $response = null;
    $params = null;    
    if (!$this->esclient->indices()->exists($params = ['index' => 'publications'])) {
        $params = [
            'index' => 'publications',
                    'body' => [
                        'settings' => [
                            'number_of_shards' => 3,
                            'number_of_replicas' => 2
                        ],                
                        'mappings' => [
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                    'enabled' => false                                   
                                ],
                                'category' => [
                                    'type' => 'text',
                                    'enabled' => false                                     
                                ],
                                'title' => [
                                    'type' => 'text',
                                    'boost' => 3                                    
                                ],
                                'slug' => [
                                    'type' => 'text',
                                    'enabled' => false                                     
                                ],
                                'introduction' => [
                                    'type' => 'text',
                                    'boost' => 2    
                                ],
                                'content' => [
                                    'type' => 'text',
                                    'boost' => 1     
                                ],
                                'create_at' => [
                                    'type' => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss',
                                    'enabled' => false 
                                ],
                                'update_at' => [
                                    'type' => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss',
                                    'enabled' => false 
                                ],
                            ]
                        ]
                    ]
                ];
        $response = $this->esclient->indices()->create($params);
        dump($response);
        }
    }

    public function insertData() {

        $response = null;
        $params = null;
        $publications = $this->manager->getRepository(Publication::class)->findAll();

        foreach ($publications as $publication) {
 
            $params['body'][] = array(
            'index' => array(
                '_index' => 'publications',
                '_id' => $publication->getId(),
                ),
            );
            
            $category = $publication->getCategory();

            $params['body'][] = [
                            'id' => $publication->getId(),
                            'title' => $publication->getTitle(),
                            'content' => $publication->getContent(),
                            'slug' => $publication->getSlug(),
                            'category' => $category->getNom(),
                            'introduction' => $publication->getIntroduction(),
                            'create_at' => date_format($publication->getCreateAt(),"Y-m-d H:i:s"),
                            'update_at' => date_format($publication->getUpdatedAt(),"Y-m-d H:i:s")
                            ];
        }
        $response = $this->esclient->bulk($params);
    }

    public function deletePublication($id) {

        $response = null;
        $params = null;
        $params = [
            'index' => 'publications',
            'id'    => $id
        ];

        if ($this->esclient->exists($params)) {
            $response = $this->esclient->delete($params);
        }
        dump($response);
        dump($params);
    }
    
    public function updatePublication(Publication $publication) {

        $response = null;
        $params = null;
        $params = [
            'index' => 'publications',
            'id'    => $publication->getId(),
            'body'  => [
                'doc' => [
                    'title' => $publication->getTitle(),
                    'content' => $publication->getContent(),
                    'slug' => $publication->getSlug(),
                    'introduction' => $publication->getIntroduction(),
                    'update_at' => date_format($publication->getUpdatedAt(),"Y-m-d H:i:s")
                ]
            ]
        ];
        $response = $this->esclient->update($params); 
        /*dump($response);
        dump($params);*/
    }

    public function indexPublication(Publication $publication) {
        
        $response = null;
        $params = null;
        $category = $publication->getCategory();
        $params = [
            'index' => 'publications',
            'id'    => $publication->getId(),
            'body'  => [
                'id' => $publication->getId(),
                'title' => $publication->getTitle(),
                'content' => $publication->getContent(),
                'slug' => $publication->getSlug(),
                'category' => $category->getNom(),
                'introduction' => $publication->getIntroduction(),
                'create_at' => date_format($publication->getCreateAt(),"Y-m-d H:i:s"),
                'update_at' => date_format($publication->getUpdatedAt(),"Y-m-d H:i:s")
            ]
        ];
        $response = $this->esclient->index($params);
        /*dump($response);
        dump($params);*/
    }

    public function search($research) {

        $params = null;
        $params = [
            'index' => 'publications',
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query' => $research,
                        'fields' => ['title','introduction','content']
                    ]
                ]
            ]
        ];
        
        $results = $this->esclient->search($params);
        return $results;
    }
}
