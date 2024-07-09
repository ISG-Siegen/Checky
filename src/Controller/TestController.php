<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionGroup;
use App\Enum\AnswerType;
use App\Repository\ConferenceInstanceRepository;
use App\Repository\QuestionRepository;
use App\Repository\UrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    // #[Route('/test', name: 'app_test', methods: 'get')]
    public function index(EntityManagerInterface $em, ConferenceInstanceRepository $cir, QuestionRepository $qr)
    {



        return new Response('Early return');

        $i1 = $cir->find('019053d3-0046-7cfa-8321-8c6c3998ed19');
        $i2 = $cir->find('019053d3-0046-7cfa-8321-8c6c3a5af0e7');

        $q11 = $qr->find('01907299-51cc-798b-9600-f75a60d85f5a');
        $q12 = new Question('Did you describe the limitations of your work?', AnswerType::FREE_TEXT_AND_JUSTIFICATION);
        $q13 = new Question('Did you discuss any potential negative societal impacts of your work?', AnswerType::FREE_TEXT_AND_JUSTIFICATION);
        $q14 = new Question('Did you read the ethics review guidelines and ensure that your paper conforms to them? https://2022.automl.cc/ethics-accessibility/', AnswerType::FREE_TEXT_AND_JUSTIFICATION);

        $qg1 = new QuestionGroup('For all authors...');

        $qg1->addQuestion($q11);
        $qg1->addQuestion($q12);
        $qg1->addQuestion($q13);
        $qg1->addQuestion($q14);

        $qg1->addConference($i1);
        $qg1->addConference($i2);


        $q21 = new Question('Did you use the same evaluation protocol for all methods being compared (e.g., same benchmarks, data (sub)sets, available resources)?', AnswerType::FREE_TEXT_AND_JUSTIFICATION);
        $q22 = new Question('Did you specify all the necessary details of your evaluation (e.g., data splits, pre-processing, search spaces, hyperparameter tuning)?', AnswerType::FREE_TEXT_AND_JUSTIFICATION);
        $q23 = new Question('Did you repeat your experiments (e.g., across multiple random seeds or splits) to account for the impact of randomness in your methods or data?', AnswerType::FREE_TEXT_AND_JUSTIFICATION);
        $q24 = new Question('Did you report the uncertainty of your results (e.g., the variance across random seeds or splits)?', AnswerType::FREE_TEXT_AND_JUSTIFICATION);


        $qg2 = new QuestionGroup('If you ran experiments...');
        $qg2->addConference($i1);
        $qg2->addConference($i2);

        $qg2->addQuestion($q21);
        $qg2->addQuestion($q22);
        $qg2->addQuestion($q23);
        $qg2->addQuestion($q24);

        $q11->addConference($i1);
        $q11->addConference($i2);
        $q12->addConference($i1);
        $q12->addConference($i2);
        $q13->addConference($i1);
        $q13->addConference($i2);
        $q14->addConference($i1);
        $q14->addConference($i2);

        $q21->addConference($i1);
        $q21->addConference($i2);
        $q22->addConference($i1);
        $q22->addConference($i2);
        $q23->addConference($i1);
        $q23->addConference($i2);
        $q24->addConference($i1);
        $q24->addConference($i2);


        $em->persist($i1);
        $em->persist($i2);

        $em->persist($qg1);
        $em->persist($qg2);

        $em->persist($q11);
        $em->persist($q12);
        $em->persist($q13);
        $em->persist($q14);

        $em->persist($q21);
        $em->persist($q22);
        $em->persist($q23);
        $em->persist($q24);


        $em->flush();

        return new Response('Created');
    }
}
