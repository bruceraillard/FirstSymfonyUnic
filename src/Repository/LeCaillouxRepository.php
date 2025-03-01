<?php

namespace App\Repository;

use App\Entity\LeCailloux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LeCailloux>
 */
class LeCaillouxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeCailloux::class);
    }

    //custom query to get lecailloux by category
    public function findByCategory(string $category): array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT lc 
            FROM App\Entity\LeCailloux lc 
            WHERE lc.category = :category
            ORDER BY lc.name ASC
        ')
            ->setParameter('category', $category)
            ->getResult();
    }


    //    /**
    //     * @return LeCailloux[] Returns an array of LeCailloux objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LeCailloux
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
