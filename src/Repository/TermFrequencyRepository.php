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
 * Repository class for managing TermFrequency entities and related operations.
 */
class TermFrequencyRepository extends ServiceEntityRepository
{
    private QuestionRepository $questionRepo;

    /**
     * Constructor to initialize the repository and inject dependencies.
     *
     * @param ManagerRegistry $registry
     * @param QuestionRepository $questionRepo Repository for accessing Question entities.
     */
    public function __construct(ManagerRegistry $registry, QuestionRepository $questionRepo)
    {
        parent::__construct($registry, TermFrequency::class);
        $this->questionRepo = $questionRepo;
    }

    /**
     * Retrieves questions similar to the input query based on term frequency analysis.
     *
     * @param string $query The query string to compare.
     * @param Uuid[] $exclude List of question UUIDs to exclude from the results.
     * @return Question[] Array of top 3 similar questions.
     */
    public function getSimilarQuestions(string $query, array $exclude)
    {
        // Tokenize the query and count occurrences of each term.
        $termCount = array_count_values(str_word_count(($query), 1));

        $questions = [];
        $questionTFs = [];

        // Fetch term frequencies for each term in the query.
        foreach (array_keys($termCount) as $term) {
            $qb = $this->createQueryBuilder('tf')
                ->join('tf.term', 'term')
                ->join('tf.question', 'q') // Required for filtering excluded questions.
                ->where('term.term = :termValue')
                ->setParameter('termValue', $term)
                ->groupBy('tf.question');

            // Exclude specified questions.
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

        // Calculate cosine similarity for each question.
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

        // Sort by similarity and fetch the top 3 questions.
        arsort($distances);
        $top3Questions = array_keys(array_slice($distances, 0, 3));

        // Filter and return the top questions.
        $filteredQuestions = array_filter($this->questionRepo->findAll(), function ($q) use ($top3Questions) {
            /** @var Question $q */
            return in_array($q->getId()->toString(), $top3Questions);
        });

        return array_values($filteredQuestions);
    }

    //    /**
    //     * @return TermFrequency[] Returns an array of TermFrequency objects
    //     * Example method for querying TermFrequencies by a specific field.
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
    //     * Example method for finding a single TermFrequency by a specific field.
    //     */
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
