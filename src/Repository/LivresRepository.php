<?php

namespace App\Repository;

use App\Entity\Livres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livres>
 */
class LivresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livres::class);
    }

    //custom query to get books by first letter of the title
    public function findByFirstLetter(?string $firstLetter): array
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT l
            FROM App\Entity\Livres l
            WHERE l.title LIKE :firstLetter
            ORDER BY l.title ASC
        ')
            ->setParameter('firstLetter', $firstLetter . '%')
            ->getResult();
    }

    //custom query to get the number of books
    public function bookCount()
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT COUNT(l)
            FROM App\Entity\Livres l
        ')
            ->getSingleScalarResult();

    }

    //    /**
    //     * @return Livres[] Returns an array of Livres objects
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

    //    public function findOneBySomeField($value): ?Livres
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
