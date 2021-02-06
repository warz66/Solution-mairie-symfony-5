<?php

namespace App\Repository;

use App\Entity\Galerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Galerie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Galerie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Galerie[]    findAll()
 * @method Galerie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Galerie::class);
    }

    public function findGaleries($trash) {
        return $this->createQueryBuilder('g')
                ->Where('g.trash = :trash')
                ->setParameter('trash', $trash)
                ->orderBy('g.id','DESC')
                ->getQuery()
                ->getResult();
    }

    public function findAllStatutOk() {
        return $this->createQueryBuilder('g')
                ->Where('g.statut = 1')
                ->andWhere('g.trash = 0')
                ->orderBy('g.id','DESC')
                ->getQuery()
                ->getResult();
    }

    /*
    ALTER TABLE galerie ADD FULLTEXT fulltext_title(title)
    ALTER TABLE galerie ADD FULLTEXT fulltext_description(description)  
    */

    public function fullTextGalerie($searchterm) {
        return $this->createQueryBuilder('g')
                ->addSelect('((MATCH (g.description) AGAINST (:searchterm)*2)+(MATCH (g.title) AGAINST (:searchterm)*2)) as score')
                ->where('((MATCH (g.description) AGAINST (:searchterm)*2)+(MATCH (g.title) AGAINST (:searchterm)*2))>0.4')
                ->andWhere('g.statut = 1')
                ->andWhere('g.trash = 0')
                ->setParameter('searchterm', $searchterm)
                ->orderBy('score', 'desc')
                ->getQuery()
                ->getResult();
    }

    // /**
    //  * @return Galerie[] Returns an array of Galerie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Galerie
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
