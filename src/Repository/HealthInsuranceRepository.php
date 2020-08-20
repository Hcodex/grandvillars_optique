<?php

namespace App\Repository;

use App\Entity\HealthInsurance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HealthInsurance|null find($id, $lockMode = null, $lockVersion = null)
 * @method HealthInsurance|null findOneBy(array $criteria, array $orderBy = null)
 * @method HealthInsurance[]    findAll()
 * @method HealthInsurance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HealthInsuranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HealthInsurance::class);
    }

    // /**
    //  * @return HealthInsurance[] Returns an array of HealthInsurance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HealthInsurance
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
