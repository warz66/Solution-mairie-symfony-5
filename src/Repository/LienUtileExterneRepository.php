<?php

namespace App\Repository;

use App\Entity\LienUtileExterne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LienUtileExterne|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienUtileExterne|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienUtileExterne[]    findAll()
 * @method LienUtileExterne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienUtileExterneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienUtileExterne::class);
    }

    // /**
    //  * @return LienUtileExterne[] Returns an array of LienUtileExterne objects
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
    public function findOneBySomeField($value): ?LienUtileExterne
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
