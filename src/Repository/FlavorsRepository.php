<?php

namespace App\Repository;

use App\Entity\Flavors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flavors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flavors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flavors[]    findAll()
 * @method Flavors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlavorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flavors::class);
    }

    // /**
    //  * @return Flavors[] Returns an array of Flavors objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Flavors
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
