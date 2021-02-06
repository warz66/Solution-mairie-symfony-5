<?php

namespace App\Repository;

use App\Entity\FlashInfoObjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FlashInfoObjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashInfoObjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashInfoObjet[]    findAll()
 * @method FlashInfoObjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashInfoObjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashInfoObjet::class);
    }

    public function findPublications($publicationId)
    {
        return $this->createQueryBuilder('f')
            ->where('f.lien_interne IN (:publicationId)')
            ->andWhere('f.choix_lien = 0')
            ->setParameter('publicationId', $publicationId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByDesc()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
        ;
    }

    // /**
    //  * @return FlashInfoObjet[] Returns an array of FlashInfoObjet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FlashInfoObjet
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
