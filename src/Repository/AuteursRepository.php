<?php

namespace App\Repository;

use App\Entity\Auteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auteurs>
 */
class AuteursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auteurs::class);
    }

    // query to find authors who have written more than one book
    public function findMultiBookAuthor()
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT a
            FROM App\Entity\Auteurs a
            JOIN a.livres l
            GROUP BY a.id
            HAVING COUNT(l) > 1
        ')
            ->getResult();
    }

    //    /**
    //     * @return Auteurs[] Returns an array of Auteurs objects
    //     */
    /*
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    //    public function findOneBySomeField($value): ?Auteurs
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    //    /**
    //     * @return Auteurs[] Returns an array of Auteurs objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Auteurs
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
