<?php

namespace App\Repository;

use App\Entity\MediaCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaCategory[]    findAll()
 * @method MediaCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaCategory::class);
    }

    // /**
    //  * @return MediaCategory[] Returns an array of MediaCategory objects
    //  */
    public function findByName($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?MediaCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
