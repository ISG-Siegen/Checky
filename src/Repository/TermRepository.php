<?php

namespace App\Repository;

use App\Entity\Term;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Term>
 * Repository class for managing Term entities.
 */
class TermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Initializes the repository with the ManagerRegistry and Term entity class.
        parent::__construct($registry, Term::class);
    }

    //    /**
    //     * @return Term[] Returns an array of Term objects
    //     * Example method for querying Term entities by a specific field.
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    /**
    //     * Example method for finding a single Term entity by a specific field.
    //     */
    //    public function findOneBySomeField($value): ?Term
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
