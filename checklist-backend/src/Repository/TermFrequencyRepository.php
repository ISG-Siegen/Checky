<?php

namespace App\Repository;

use App\Entity\TermFrequency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Question;
use App\Util\DatabaseUtil;
use App\Util\MathUtil;

/**
 * @extends ServiceEntityRepository<TermFrequency>
 */
class TermFrequencyRepository extends ServiceEntityRepository
{

    private QuestionRepository $questionRepo;

    public function __construct(ManagerRegistry $registry, QuestionRepository $questionRepo)
    {
        parent::__construct($registry, TermFrequency::class);
        $this->questionRepo = $questionRepo;
    }


    /**
     * @param Uuid[] $exclude
     * @return Question[]
     */
    public function getSimilarQuestions(string $query, array $exclude)
    {
        $termCount = array_count_values(str_word_count(($query), 1));

        $questions = [];
        $questionTFs = [];

        foreach (array_keys($termCount) as $term) {
            $qb = $this->createQueryBuilder('tf')
                ->join('tf.term', 'term')
                ->join('tf.question', 'q') // Required for filter below
                ->where('term.term = :termValue')
                ->setParameter('termValue', $term)
                ->groupBy('tf.question');

            // Filter excluded questions
            $qb = DatabaseUtil::filterQuestionsById($qb, $exclude);

            /** @var TermFrequency[] */
            $tfs = $qb->getQuery()->getResult();
            foreach ($tfs as $tf) {
                $q = $tf->getQuestion();
                $questions[] = $q;
                $questionTFs[$term][$q->getId()->toString()] = $tf->getFrequency();
            }
        }

        $queryTfArr = array_values($termCount);

        $distances = [];

        foreach ($questions as $q) {

            $questTfArr = [];
            foreach (array_keys($termCount) as $term) {
                if (isset($questionTFs[$term][$q->getId()->toString()])) {
                    $questTfArr[] = $questionTFs[$term][$q->getId()->toString()];
                } else {
                    $questTfArr[] = 0;
                }
            }

            $cosSim = MathUtil::dot($queryTfArr, $questTfArr) / (MathUtil::norm($queryTfArr) * MathUtil::norm($questTfArr));
            $distances[$q->getId()->toString()] = $cosSim;
        }

        arsort($distances);

        $top3Questions = array_keys(array_slice($distances, 0, 3));
        $filteredQuestions = array_filter($this->questionRepo->findAll(), function ($q) use ($top3Questions) {
            /** @var Question $q */
            return in_array($q->getId()->toString(), $top3Questions);
        });

        return array_values($filteredQuestions);
    }


    //    /**
    //     * @return TermFrequency[] Returns an array of TermFrequency objects
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

    //    public function findOneBySomeField($value): ?TermFrequency
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
