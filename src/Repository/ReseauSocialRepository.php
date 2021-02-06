<?php

namespace App\Repository;

use App\Entity\ReseauSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ReseauSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReseauSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReseauSocial[]    findAll()
 * @method ReseauSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReseauSocialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReseauSocial::class);
    }

    // /**
    //  * @return ReseauSocial[] Returns an array of ReseauSocial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReseauSocial
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
