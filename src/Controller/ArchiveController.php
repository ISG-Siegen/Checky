<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\ConferenceInstance;
use App\Repository\ConferenceInstanceRepository;
use App\Repository\ConferenceRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('archive/')] // Base route for the ArchiveController.
#[OA\Tag('archive')] // Tag for OpenAPI documentation grouping all archive-related endpoints.
class ArchiveController extends AbstractController
{
    #[Route('conferences', methods: 'get')] // Route for retrieving all conferences.
    #[OA\Response(
        response: 200,
        description: 'Returns a list of all conferences',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Conference::class, groups: ['archive:get_names'])) // Defines response structure.
        )
    )]
    public function getConferences(ConferenceRepository $conferenceRepo, SerializerInterface $serializer): JsonResponse
    {
        // Retrieves all conferences using the repository.
        $res = $conferenceRepo->findAll();
        // Returns the list of conferences as a JSON response, applying serialization groups.
        return $this->json($res, context: ['groups' => ['archive:get_names']]);
    }

    #[Route('{id}/instances', methods: 'get')] // Route for retrieving instances of a specific conference.
    #[OA\Response(
        response: 200,
        description: 'Returns a list of the instance ids of this conference and their year',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ConferenceInstance::class, groups: ['archive:years']))
        )
    )]
    public function getInstances(string $id, ConferenceRepository $conferenceRepo, SerializerInterface $serializer): JsonResponse
    {
        // Finds the conference by its ID and retrieves its instances.
        $res = $conferenceRepo->find($id)->getInstances();
        if (!$res) {
            // Returns a 404 response if the conference is not found.
            return new JsonResponse('Conference not found', 404);
        }

        // Returns the list of conference instances as a JSON response, applying serialization groups.
        return $this->json($res, context: ['groups' => ['archive:years']]);
    }

    #[Route('{id}/details', methods: 'get')] // Route for retrieving details of a specific conference instance.
    #[OA\Response(
        response: 200,
        description: 'Returns details about the given conference instance',
        content: new OA\JsonContent(
            ref: new Model(type: ConferenceInstance::class, groups: ['archive:details']) // Defines the response structure.
        )
    )]
    public function getInstanceDetails(string $id, ConferenceInstanceRepository $conferenceInstanceRepo, SerializerInterface $serializer): JsonResponse
    {
        // Finds the conference instance by its ID.
        $res = $conferenceInstanceRepo->find($id);
        if (!$res) {
            // Returns a 404 response if the instance is not found.
            return new JsonResponse('Instance not found', 404);
        }

        // Returns the conference instance details as a JSON response, applying serialization groups.
        return $this->json($res, context: ['groups' => ['archive:details']]);
    }
}
