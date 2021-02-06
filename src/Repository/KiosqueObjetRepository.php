<?php

namespace App\Repository;

use App\Entity\KiosqueObjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method KiosqueObjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method KiosqueObjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method KiosqueObjet[]    findAll()
 * @method KiosqueObjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KiosqueObjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KiosqueObjet::class);
    }

    public function findAllStatutOk($limit = null) {
        $qb = $this->createQueryBuilder('k')
                ->Where('k.statut = 1')
                ->orderBy('k.id','DESC');

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->getQuery()
                  ->getResult();
    }

    // /**
    //  * @return KiosqueObjet[] Returns an array of KiosqueObjet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KiosqueObjet
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
