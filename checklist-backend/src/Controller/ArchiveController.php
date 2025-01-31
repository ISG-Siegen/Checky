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

#[Route('archive/')]
#[OA\Tag('archive')]
class ArchiveController extends AbstractController
{
    #[Route('conferences', methods: 'get')]
    #[OA\Response(
        response: 200,
        description: 'Returns a list of all conferences',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Conference::class, groups: ['archive:get_names'])) //
        )
    )]
    public function getConferences(ConferenceRepository $conferenceRepo, SerializerInterface $serializer): JsonResponse
    {
        $res = $conferenceRepo->findAll();
        return $this->json($res, context: ['groups' => ['archive:get_names']]);
    }

    #[Route('{id}/instances', methods: 'get')]
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

        $res = $conferenceRepo->find($id)->getInstances();
        if (!$res) {
            return new JsonResponse('Conference not found', 404);
        }

        return $this->json($res, context: ['groups' => ['archive:years']]);
    }

    #[Route('{id}/details', methods: 'get')]
    #[OA\Response(
        response: 200,
        description: 'Returns a details about the given conference instance',
        content: new OA\JsonContent(
            ref: new Model(type: ConferenceInstance::class, groups: ['archive:details'])
        )
    )]
    public function getInstanceDetails(string $id, ConferenceInstanceRepository $conferenceInstanceRepo, SerializerInterface $serializer): JsonResponse
    {
        $res = $conferenceInstanceRepo->find($id);
        if (!$res) {
            return new JsonResponse('Instance not found', 404);
        }

        return $this->json($res, context: ['groups' => ['archive:details']]);
    }
}
