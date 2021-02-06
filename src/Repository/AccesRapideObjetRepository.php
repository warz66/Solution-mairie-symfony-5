<?php

namespace App\Repository;

use App\Entity\AccesRapideObjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AccesRapideObjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccesRapideObjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccesRapideObjet[]    findAll()
 * @method AccesRapideObjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccesRapideObjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccesRapideObjet::class);
    }

    public function findPublications($publicationId)
    {
        return $this->createQueryBuilder('a')
            ->where('a.lien_publication IN (:publicationId)')
            ->setParameter('publicationId', $publicationId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByDesc()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
        ;
    }

    // /**
    //  * @return AccesRapideObjet[] Returns an array of AccesRapideObjet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccesRapideObjet
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
