<?php

namespace App\Repository;

use App\Entity\NewsletterSubscriber;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method NewsletterSubscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterSubscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterSubscriber[]    findAll()
 * @method NewsletterSubscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterSubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterSubscriber::class);
    }

    public function findByNonConfirmes()
    {   
        return $this->createQueryBuilder('s')
                    ->where('s.confirmation = 0')
                    ->getQuery()
                    ->getResult(); 
    }

    public function findByIpAdress($ipAdress, $delayMinutes)
    {   
        $timeAgo = new \DateTimeImmutable(sprintf('-%d minutes', $delayMinutes));

        return $this->createQueryBuilder('s')
                    ->select('COUNT(s)')
                    ->where('s.created_at >= :createdAt')
                    ->andWhere('s.ipAdress = :ipAdress')
                    ->setParameters([
                        'ipAdress' => $ipAdress,
                        'createdAt' => $timeAgo
                    ])
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    // /**
    //  * @return Newsletter[] Returns an array of Newsletter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Newsletter
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
