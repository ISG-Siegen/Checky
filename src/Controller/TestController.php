<?php

namespace App\Controller;

use App\Entity\Checklist;
use App\Entity\Source;
use App\Entity\Url;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(EntityManagerInterface $em, ValidatorInterface $validator): Response
    {


        return new Response('Early return');

        $checklist = new Checklist('AutoML 24 Submission Checklist');

        $source = new Source('AUTOML24 Conference ');
        $source->setYear(2024);
        $source->setDescription('International Conference on Automated Machine Learning. September 09.-12. in Paris.');
        $checklist->addPublishedIn($source);

        $url = new Url('AutoML 2024 Checklist', 'https://checklist.recommender-systems.com/checklists/automl2024 checklist.pdf');
        $checklist->addUrl($url);

        $em->persist($checklist);
        $em->persist($source);
        $em->persist($url);
        $em->flush();

        return new Response('Created');
    }
}
