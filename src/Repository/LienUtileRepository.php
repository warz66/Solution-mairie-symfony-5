<?php

namespace App\Repository;

use App\Entity\LienUtile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LienUtile|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienUtile|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienUtile[]    findAll()
 * @method LienUtile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienUtileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienUtile::class);
    }

    // /**
    //  * @return LienUtile[] Returns an array of LienUtile objects
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
    public function findOneBySomeField($value): ?LienUtile
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
