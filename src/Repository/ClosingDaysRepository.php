<?php

namespace App\Repository;

use App\Entity\ClosingDays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClosingDays|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClosingDays|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClosingDays[]    findAll()
 * @method ClosingDays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClosingDaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClosingDays::class);
    }

    public function getClosingDays()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ClosingDays[] Returns an array of ClosingDays objects
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
    public function findOneBySomeField($value): ?ClosingDays
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
