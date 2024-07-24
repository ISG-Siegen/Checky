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

#[Route('/test')]
class TestController extends AbstractController
{

    #[Route('/tf', methods: 'get')]
    public function buildTermFrequencies(QuestionRepository $questionRepo, TermRepository $termRepo, TermFrequencyRepository $tfRepo, EntityManagerInterface $em)
    {
        $debug = $this->getParameter('kernel.debug');
        if (!$debug) {
            return new Response('Only enabled in debug mode!');
        }

        // Clear all frequency and term entries
        foreach ($tfRepo->findAll() as $tf) {
            $em->remove($tf);
        }

        foreach ($termRepo->findAll() as $term) {
            $em->remove($term);
        }

        /** @var Question[] */
        $questions = $questionRepo->findAll();
        foreach ($questions as $question) {

            $questionText = strtolower($question->getQuestion());
            $termCount = array_count_values(str_word_count(($questionText), 1));

            foreach ($termCount as $term => $count) {

                // If term does not exist in db, create it
                /** @var Term | null */
                $termObj = $termRepo->findOneBy(['term' => $term]);
                if (!$termObj) {
                    $termObj = new Term($term);
                }

                $termFreq = new TermFrequency($question, $termObj, $count);

                $em->persist($termObj);
                $em->persist($termFreq);
                $em->flush();
            }
        }

        return new Response('Done!');
    }
}
