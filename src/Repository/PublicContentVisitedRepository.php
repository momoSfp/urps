<?php

namespace App\Repository;

use App\Entity\PublicContentVisited;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PublicContentVisited|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicContentVisited|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicContentVisited[]    findAll()
 * @method PublicContentVisited[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicContentVisitedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PublicContentVisited::class);
    }

    public function findByTodayDate(): ?PublicContentVisited
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date = :val')
            ->setParameter('val', DATE_FORMAT(new \Datetime(), "Y-m-d"))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return PublicContentVisited[] Returns an array of PublicContentVisited objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PublicContentVisited
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
