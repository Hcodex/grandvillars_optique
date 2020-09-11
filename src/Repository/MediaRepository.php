<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ContainerInterface $container = null)
    {
        parent::__construct($registry, Media::class);
        $this->container = $container;
    }

    // /**
    //  * @return Media[] Returns an array of Media objects
    //  */

    public function findByCategory($value)
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.mediaCategory', 'c')
            ->andWhere('c.name= :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


    public function getMedias()
    {
        $media = [];
        $categories = array_merge($this->container->getParameter('media.categories'),$this->container->getParameter('media.lockedCategories'));
        foreach ($categories as $categorie) {
           $media[$categorie] = $this->findByCategory($categorie);
        }
        return $media;
    }
    /*
    public function findOneBySomeField($value): ?Media
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
