<?php

namespace App\Repository;

use App\Entity\Horaire2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Horaire2|null find($id, $lockMode = null, $lockVersion = null)
 * @method Horaire2|null findOneBy(array $criteria, array $orderBy = null)
 * @method Horaire2[]    findAll()
 * @method Horaire2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Horaire2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Horaire2::class);
    }

    // /**
    //  * @return Horaire2[] Returns an array of Horaire2 objects
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
    public function findOneBySomeField($value): ?Horaire2
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
