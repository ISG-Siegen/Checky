<?php

namespace App\Controller;

use App\Repository\ChecklistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class ChecklistController extends AbstractController
{
    #[Route('/checklist/all')]
    public function get_all(ChecklistRepository $checklistRepository, SerializerInterface $serializer): Response
    {
        $checklists = $checklistRepository->findAll();

        return $this->json($checklists);
    }


    #[Route('/checklist/{id}')]
    public function get_by_id(Uuid $id, ChecklistRepository $checklistRepository)
    {
        $checklist = $checklistRepository->find($id);

        return $this->json($checklist);
    }
}
