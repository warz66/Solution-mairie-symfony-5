<?php

namespace App\Repository;

use App\Entity\AccesRapide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AccesRapide|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccesRapide|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccesRapide[]    findAll()
 * @method AccesRapide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccesRapideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccesRapide::class);
    }

    // /**
    //  * @return AccesRapide[] Returns an array of AccesRapide objects
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
    public function findOneBySomeField($value): ?AccesRapide
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
