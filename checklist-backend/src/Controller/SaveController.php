<?php

namespace App\Controller;

use App\Dto\SaveChecklistRequest;
use App\Dto\SaveQuestionRequest;
use App\Entity\SavedChecklist;
use App\Entity\SavedQuestion;
use App\Repository\QuestionRepository;
use App\Repository\SavedChecklistRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Uid\Uuid;

#[OA\Tag('save')]
#[Route('/save')]
class SaveController extends AbstractController
{
    #[Route('/{uuid}', methods: 'get', name: 'by_uuid')]
    #[OA\Parameter(
        name: 'uuid',
        in: 'path',
        description: 'The uuid of the saved checklist to retrieve',
        schema: new OA\Schema(
            type: 'string',
            format: 'uuid'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the saved checklist with given uuid',
        content: new Model(type: SavedChecklist::class, groups: ['save:savedChecklist'])
    )]
    public function get(SavedChecklistRepository $saveRepo, Uuid $uuid): JsonResponse
    {
        return $this->json($saveRepo->find($uuid), context: ['groups' => ['save:savedChecklist']]);
    }


    #[Route('', methods: 'post', name: 'save')]
    #[OA\Post(
        summary: 'Saves or updates a checklist',
        description: 'If no or an unknown uuid is given, a new checklist is created. If the given uuid exist the corresponding checklist is updated.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns uuid of the newly created or updated checklist',
        content: new OA\JsonContent(
            type: 'string',
            format: 'uuid'
        )
    )]
    public function save(
        #[MapRequestPayload(serializationContext: ['groups' => ['save:updateRequest']])] SaveChecklistRequest $request,
        SavedChecklistRepository $savedChecklistRepo,
        QuestionRepository $questionRepo,
        EntityManagerInterface $em
    ): JsonResponse {

        /** @var SavedQuestion[] */
        $newQuestions = [];
        foreach ($request->questionRequests as $qR) {
            /** @var SavedQuestion */
            $requestedQ = $qR->question;
            $ogQuestion = null;
            if ($qR->originalQuestion) {
                $ogQuestion = $questionRepo->find($qR->originalQuestion);
            }
            $q = new SavedQuestion($requestedQ->getQuestion(), $requestedQ->getAnswerType(), $ogQuestion);
            array_push($newQuestions, $q);
            $em->persist($q);
        }


        /** @var SavedChecklist | null */
        $oldChecklist = null;
        if ($request->uuid) {
            $oldChecklist = $savedChecklistRepo->find($request->uuid);
        }

        if ($oldChecklist) {
            foreach ($oldChecklist->getQuestions() as $oldQ) {
                $em->remove($oldQ);
            }
            $oldChecklist->setQuestions($newQuestions);
            $oldChecklist->setName($request->name);
            $oldChecklist->setUpdatedAt(new DateTime());
            $em->persist($oldChecklist);
            $em->flush();
            return $this->json($oldChecklist->getId());
        }

        $newChecklist = new SavedChecklist($request->name);
        foreach ($newQuestions as $newQ) {
            $newChecklist->addQuestion($newQ);
        }

        $em->persist($newChecklist);
        $em->flush();

        return $this->json($newChecklist->getId());
    }
}
