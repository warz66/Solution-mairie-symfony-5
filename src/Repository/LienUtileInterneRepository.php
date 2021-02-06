<?php

namespace App\Repository;

use App\Entity\LienUtileInterne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LienUtileInterne|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienUtileInterne|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienUtileInterne[]    findAll()
 * @method LienUtileInterne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienUtileInterneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienUtileInterne::class);
    }

    public function findPublications($publicationId)
    {
        return $this->createQueryBuilder('l')
            ->where('l.lien_publication IN (:publicationId)')
            ->setParameter('publicationId', $publicationId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByLienPublication($publicationId)
    {
        return $this->createQueryBuilder('l')
            ->where('l.lien_publication = :publicationId') 
            ->setParameter('publicationId', $publicationId)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return LienUtileInterne[] Returns an array of LienUtileInterne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LienUtileInterne
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
