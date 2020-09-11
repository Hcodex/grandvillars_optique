<?php

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ContainerInterface $container = null)
    {
        parent::__construct($registry, Content::class);
        $this->container = $container;
    }

     
    /** 
    * Récupère le contenu d'un bloc pas sa catégorie
    */
    public function findOneByCategoryField($value)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.content_category','d')
            ->andWhere('d.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findByCategoryField($value)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.content_category','d')
            ->andWhere('d.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getContents()
    {
        $contents = [];
        $categories = $this->container->getParameter('contentCategories');
        foreach ($categories as $categorie) {
           $contents[$categorie] = $this->findByCategoryField($categorie);
        }
        return $contents;
    }


    /*
    public function findOneBySomeField($value): ?Content
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
