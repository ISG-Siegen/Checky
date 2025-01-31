<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use App\Util\DatabaseUtil;

/**
 * @extends ServiceEntityRepository<Question>
 * Repository class for managing Question entities.
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Initializes the repository with the registry and Question entity class.
        parent::__construct($registry, Question::class);
    }

    /**
     * Finds questions where the query string matches part of the question text.
     *
     * @param string $query The string to search for within questions.
     * @return Question[] Array of matching Question entities.
     */
    public function findLike(string $query): array
    {
        $qb = $this->createQueryBuilder('q');
        $qb->where($qb->expr()->like('q.question', $qb->expr()->literal('%' . $query . '%')));
        return $qb->getQuery()->getResult();
    }

    /**
     * Fetches up to three random questions, excluding those with IDs in the $except array.
     *
     * @param Uuid[] $except Array of UUIDs to exclude from the results.
     * @return Question[] Array of up to three random Question entities.
     */
    public function getThreeRandom(array $except): array
    {
        // Counts the total number of questions excluding specified IDs.
        $countQb = $this->createQueryBuilder('q');
        $countQb = DatabaseUtil::filterQuestionsById($countQb, $except);
        $countQb->select('count(q)');
        $count = $countQb->getQuery()->getSingleScalarResult();

        $res = [];

        // Fetches up to three random questions.
        for ($_ = 0; $_ < min(3, $count); $_++) {
            $r = rand(0, $count - 1);

            $resQb = $this->createQueryBuilder('q');
            $resQb = DatabaseUtil::filterQuestionsById($resQb, $except);
            $question = $resQb->setFirstResult($r)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();

            array_push($res, $question);
        }

        return $res;
    }



    //    /**
    //     * @return Question[] Returns an array of Question objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Question
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
