<?php

namespace App\Repository;

use App\Entity\Tutor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tutor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tutor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tutor[]    findAll()
 * @method Tutor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tutor::class);
    }

    // /**
    //  * @return Tutor[] Returns an array of Tutor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tutor
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
