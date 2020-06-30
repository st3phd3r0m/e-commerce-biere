<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\Flavors;
use App\Entity\Products;
use App\Entity\Volumes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function search(string $searchterm)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('MATCH_AGAINST(p.name, p.description) AGAINST (:searchterm boolean)>0')
            ->setParameter('searchterm', $searchterm)
            ->getQuery()->getResult();
    }


    public function isUserBuyer(string $firstname, string $lastname, int $id)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id')
            ->innerJoin('p.carts', 'c')
            ->innerJoin('c.orders', 'o')
            ->innerJoin('o.user', 'u')
            ->where('u.firstname = :firstname AND u.lastname = :lastname AND p.id = :id')
            ->setParameter('firstname', $firstname)
            ->setParameter('lastname', $lastname)
            ->setParameter('id', $id)
            ->getQuery()->getResult();
    }

	public function filterProducts(Categories $categorie, string $minPrice = null, string $maxPrice = null, string $degree = null, string $volume = null)
	{
		// Ce n'est pas une requête SQL mais DQL
		$query = $this->createQueryBuilder('p');

		if ($minPrice !== null) {
			$query->andWhere('p.price BETWEEN :minPrice AND :maxPrice')
				->setParameter('minPrice', $minPrice)
				->setParameter('maxPrice', $maxPrice);
		}

		if ($degree !== null) {

			$minDegree = 0;
			$maxDegree = 999999;

			switch ($degree) {
				case 1:
					$minDegree = 0;
					$maxDegree = 5;
					break;

				case 2:
					$minDegree = 5.1;
					$maxDegree = 8;
					break;

				case 3:
					$minDegree = 8.1;
					$maxDegree = 10;
					break;

				case 4:
					$minDegree = 10.1;
					$maxDegree = 999999;
					break;
			}


			$query->andWhere('p.degree BETWEEN :minDegree AND :maxDegree')
				->setParameter('minDegree', $minDegree)
				->setParameter('maxDegree', $maxDegree);
		}

		// A voir ;)
		if ($volume !== null) {
			$query->andWhere('p.volume < :volume')
				->setParameter('volume', $volume);
		}

		$query->andWhere('p.category = :category')
			->setParameter('category', $categorie)
			->getQuery()
		;

		return $query;
	}

    // /**
    //  * @return Products[] Returns an array of Products objects
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
    public function findOneBySomeField($value): ?Products
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
