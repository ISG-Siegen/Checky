<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use App\Util\DatabaseUtil;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @return Question[]
     */
    public function findLike(string $query): array
    {
        $qb = $this->createQueryBuilder('q');

        $qb->where($qb->expr()->like('q.question', $qb->expr()->literal('%' . $query . '%')));

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Uuid[] $except
     * @return Question[]
     */
    public function getThreeRandom(array $except): array
    {
        $countQb = $this->createQueryBuilder('q');
        $countQb = DatabaseUtil::filterQuestionsById($countQb, $except);
        $countQb->select('count(q)');
        $count = $countQb->getQuery()
            ->getSingleScalarResult();

        $res = [];

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
