<?php

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Content::class);
    }

    public function findAllActive()
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.active = true')
        ->orderBy('c.id', 'Desc')
        ->getQuery()
        ->getResult()
        ;
    }

    public function findAllActiveAndPublic()
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.active = true and c.public = true')
        ->orderBy('c.id', 'Desc')
        ->getQuery()
        ->getResult()
        ;
    }

    public function getCountActive()
    {
        $qb = $this->createQueryBuilder('c')
        ->andWhere('c.active = true')
        ->orderBy('c.id', 'Desc');

        return count($qb->getQuery()->getResult());
    }

    // /**
    //  * @return Content[] Returns an array of Content objects
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
