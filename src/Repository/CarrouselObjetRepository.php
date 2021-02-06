<?php

namespace App\Repository;

use App\Entity\CarrouselObjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CarrouselObjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarrouselObjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarrouselObjet[]    findAll()
 * @method CarrouselObjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarrouselObjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarrouselObjet::class);
    }

    public function findPublications($publicationId)
    {
        return $this->createQueryBuilder('c')
            ->where('c.lien_publication IN (:publicationId)')
            ->setParameter('publicationId', $publicationId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByDesc()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
        ;
    }

    // /**
    //  * @return CarrouselObjet[] Returns an array of CarrouselObjet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarrouselObjet
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
