<?php

namespace App\Repository;

use App\Entity\Volumes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Volumes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Volumes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Volumes[]    findAll()
 * @method Volumes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolumesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Volumes::class);
    }

    // /**
    //  * @return Volumes[] Returns an array of Volumes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Volumes
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
