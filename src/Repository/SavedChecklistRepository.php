<?php

namespace App\Repository;

use App\Entity\SavedChecklist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SavedChecklist>
 * Repository class for managing SavedChecklist entities.
 */
class SavedChecklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Initializes the repository with the registry and SavedChecklist entity class.
        parent::__construct($registry, SavedChecklist::class);
    }

    //    /**
    //     * @return SavedChecklist[] Returns an array of SavedChecklist objects
    //     * Example method for querying SavedChecklists by a specific field.
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
    //     * Example method for finding a single SavedChecklist by a specific field.
    //     */
    //    public function findOneBySomeField($value): ?SavedChecklist
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
