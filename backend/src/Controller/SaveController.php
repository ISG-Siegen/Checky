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

#[OA\Tag('save')] // Groups the endpoints under the "save" tag in the API documentation.
#[Route('/save')] // Base route for the SaveController.
class SaveController extends AbstractController
{
    #[Route('/{uuid}', methods: 'get', name: 'by_uuid')] // Route to retrieve a saved checklist by UUID.
    #[OA\Parameter(
        name: 'uuid',
        in: 'path',
        description: 'The UUID of the saved checklist to retrieve',
        schema: new OA\Schema(
            type: 'string',
            format: 'uuid'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the saved checklist with given UUID',
        content: new Model(type: SavedChecklist::class, groups: ['save:savedChecklist'])
    )]
    public function get(SavedChecklistRepository $saveRepo, Uuid $uuid): JsonResponse
    {
        // Fetches the saved checklist by UUID and returns it as a JSON response.
        return $this->json($saveRepo->find($uuid), context: ['groups' => ['save:savedChecklist']]);
    }

    #[Route('', methods: 'post', name: 'save')] // Route to save or update a checklist.
    #[OA\Post(
        summary: 'Saves or updates a checklist',
        description: 'If no or an unknown UUID is given, a new checklist is created. If the given UUID exists, the corresponding checklist is updated.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the UUID of the newly created or updated checklist',
        content: new OA\JsonContent(
            type: 'string',
            format: 'uuid'
        )
    )]
    public function save(
        #[MapRequestPayload(serializationContext: ['groups' => ['save:updateRequest']])] SaveChecklistRequest $request, // Maps the incoming request payload to the `SaveChecklistRequest` DTO.
        SavedChecklistRepository $savedChecklistRepo,
        QuestionRepository $questionRepo,
        EntityManagerInterface $em
    ): JsonResponse {

        // Creates or updates a checklist based on the incoming request data.

        /** @var SavedQuestion[] $newQuestions */
        $newQuestions = [];
        foreach ($request->questionRequests as $qR) {
            // Processes each question request.
            /** @var SavedQuestion */
            $requestedQ = $qR->question;
            $ogQuestion = null;
            if ($qR->originalQuestion) {
                // Retrieves the original question if specified.
                $ogQuestion = $questionRepo->find($qR->originalQuestion);
            }
            // Creates a new SavedQuestion entity.
            $q = new SavedQuestion($requestedQ->getQuestion(), $requestedQ->getAnswerType(), $ogQuestion);
            array_push($newQuestions, $q);
            $em->persist($q); // Marks the entity for persistence.
        }

        $oldChecklist = null;
        if ($request->uuid) {
            // Retrieves the existing checklist if a UUID is provided.
            /** @var SavedChecklist|null $oldChecklist */
            $oldChecklist = $savedChecklistRepo->find($request->uuid);
        }

        if ($oldChecklist) {
            // Updates the existing checklist.
            foreach ($oldChecklist->getQuestions() as $oldQ) {
                $em->remove($oldQ); // Removes old questions.
            }
            $oldChecklist->setQuestions($newQuestions);
            $oldChecklist->setName($request->name);
            $oldChecklist->setDescription($request->description);
            $oldChecklist->setUpdatedAt(new DateTime()); // Updates the timestamp.
            $em->persist($oldChecklist);
            $em->flush();
            return $this->json($oldChecklist->getId()); // Returns the UUID of the updated checklist.
        }

        // Creates a new checklist if no UUID is provided or the UUID does not exist.
        $newChecklist = new SavedChecklist($request->name, $request->description);
        foreach ($newQuestions as $newQ) {
            $newChecklist->addQuestion($newQ); // Adds new questions to the checklist.
        }

        $em->persist($newChecklist); // Marks the new checklist for persistence.
        $em->flush(); // Saves all changes to the database.

        return $this->json($newChecklist->getId()); // Returns the UUID of the new checklist.
    }
}
