<?php

namespace App\Repository;

use App\Entity\FlashInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FlashInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashInfo[]    findAll()
 * @method FlashInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashInfo::class);
    }

    // /**
    //  * @return FlashInfo[] Returns an array of FlashInfo objects
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
    public function findOneBySomeField($value): ?FlashInfo
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
