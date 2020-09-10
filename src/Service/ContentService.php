<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContentService
{
    private $manager;

    public function __construct(EntityManagerInterface $manager,  ContainerInterface $container = null)
    {
        $this->manager = $manager;
        $this->container = $container;
    }

    public function getContents()
    {
        $contents = [];
        $categories = $this->container->getParameter('contentCategories');
        foreach ($categories as $categorie) {
           $contents[$categorie] = $this->contentQuery($categorie);
        }
        return $contents;
    }

    public function contentQuery($value)
    {
        return $this->manager->createQuery(
            "SELECT c
            FROM App\Entity\Content c
            JOIN c.content_category d
            WHERE d.name = :name
            "
        )
        ->setParameter('name', $value)
        ->getResult();
    } 
}