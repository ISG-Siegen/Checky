<?php

namespace App\Repository;

use App\Entity\SavedQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SavedQuestion>
 * Repository class for managing SavedQuestion entities.
 */
class SavedQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Initializes the repository with the registry and SavedQuestion entity class.
        parent::__construct($registry, SavedQuestion::class);
    }

    //    /**
    //     * @return SavedQuestion[] Returns an array of SavedQuestion objects
    //     * Example method for querying SavedQuestions by a specific field.
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    /**
    //     * Example method for finding a single SavedQuestion by a specific field.
    //     */
    //    public function findOneBySomeField($value): ?SavedQuestion
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
