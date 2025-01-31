<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Entity\Term;
use App\Entity\TermFrequency;
use App\Repository\TermFrequencyRepository;
use App\Repository\TermRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/test')] // Base route for all endpoints in this controller.
class TestController extends AbstractController
{
    #[Route('/tf', methods: 'get')] // Route to build term frequencies from question text.
    public function buildTermFrequencies(
        QuestionRepository $questionRepo, 
        TermRepository $termRepo, 
        TermFrequencyRepository $tfRepo, 
        EntityManagerInterface $em
    ): Response {
        // Ensures this functionality only runs in debug mode.
        $debug = $this->getParameter('kernel.debug');
        if (!$debug) {
            return new Response('Only enabled in debug mode!');
        }

        // Clear all existing term frequency and term entries from the database.
        foreach ($tfRepo->findAll() as $tf) {
            $em->remove($tf); // Deletes term frequency records.
        }

        foreach ($termRepo->findAll() as $term) {
            $em->remove($term); // Deletes term records.
        }

        // Fetches all questions from the repository.
        /** @var Question[] $questions */
        $questions = $questionRepo->findAll();

        // Processes each question to calculate term frequencies.
        foreach ($questions as $question) {
            // Converts question text to lowercase and counts occurrences of each word.
            $questionText = strtolower($question->getQuestion());
            $termCount = array_count_values(str_word_count($questionText, 1));

            foreach ($termCount as $term => $count) {
                // Checks if the term already exists in the database; if not, creates it.
                /** @var Term|null $termObj */
                $termObj = $termRepo->findOneBy(['term' => $term]);
                if (!$termObj) {
                    $termObj = new Term($term); // Creates a new term entity.
                }

                // Creates a new term frequency entity linking the question and term.
                $termFreq = new TermFrequency($question, $termObj, $count);

                // Marks the term and term frequency for persistence.
                $em->persist($termObj);
                $em->persist($termFreq);

                // Saves changes to the database.
                $em->flush();
            }
        }

        // Returns a success response once processing is complete.
        return new Response('Done!');
    }
}