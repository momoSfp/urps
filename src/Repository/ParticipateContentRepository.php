<?php

namespace App\Repository;

use App\Entity\ParticipateContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ParticipateContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipateContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipateContent[]    findAll()
 * @method ParticipateContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipateContentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParticipateContent::class);
    }

    // /**
    //  * @return ParticipateContent[] Returns an array of ParticipateContent objects
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
    public function findOneBySomeField($value): ?ParticipateContent
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
