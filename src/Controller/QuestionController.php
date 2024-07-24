<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Repository\TermFrequencyRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

#[Route('questions/')]
#[OA\Tag('questions')]
class QuestionController extends AbstractController
{
    #[Route('', name: 'app_question', methods: 'get')]
    #[OA\Response(
        response: 200,
        description: 'Returns all questions',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Question::class, groups: ['question:get_questions']))
        )
    )]
    public function getQuestions(QuestionRepository $questionRepo): JsonResponse
    {
        $res = $questionRepo->findAll();
        return $this->json($res, context: ['groups' => ['question:get_questions']]);
    }

    #[Route('search', name: 'app_question_search', methods: 'get')]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'Searches for questions using given query',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Question::class, groups: ['question:get_questions']))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'No query was given',
        content: new OA\JsonContent(type: 'string', example: 'Query missing')
    )]
    public function searchQuestions(Request $request, QuestionRepository $questionRepo): JsonResponse
    {
        $query = $request->query->get('query');
        if (!$query) {
            return $this->json('Query missing', 400);
        }


        $res = $questionRepo->findLike($query);
        return $this->json($res, context: ['groups' => ['question:get_questions']]);
    }

    #[Route('random', name: 'app_question_random', methods: 'get')]
    #[OA\Parameter(
        name: 'except[]',
        in: 'query',
        description: 'IDs of questions to exclude from the random draw',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'string',
                format: 'uuid'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns three random questions',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Question::class, groups: ['question:get_questions']))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'One of the given UUIDs has an invalid format',
        content: new OA\JsonContent(type: 'string', example: 'Invalid UUID')
    )]
    public function randomQuestions(Request $request, QuestionRepository $questionRepo): JsonResponse
    {
        $except = $request->query->all('except');

        $uuids = [];
        foreach ($except as $e) {
            if (!Uuid::isValid($e)) {
                return $this->json('Invalid UUID', 400);
            }
            array_push($uuids, new Uuid($e));
        }

        $res = $questionRepo->getThreeRandom($uuids);
        return $this->json($res, context: ['groups' => ['question:get_questions']]);
    }

    #[Route('similar', name: 'app_question_similar', methods: 'get')]
    #[OA\Get(
        summary: 'Fetches questions similar to a query',
        description: 'Searches for questions in the database that are similar to the given query. If one ore more UUIDs are given using the exclude parameter, questions with these UUIDs are filtered from the result.'
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        description: 'The query that similar questions should be retrieved for',
        schema: new OA\Schema(
            type: 'string',
        )
    )]
    #[OA\Parameter(
        name: 'exclude[]',
        in: 'query',
        description: 'IDs of questions to exclude from the recommendation',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'string',
                format: 'uuid'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns three questions similar to the query',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Question::class, groups: ['question:get_questions']))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'No query was given or one of the given UUIDs has an invalid format',
        content: new OA\JsonContent(
            type: 'string',
            examples: [
                new OA\Examples(summary: 'Query missing', example: 'Query missing', value: 'Query missing'),
                new OA\Examples(summary: 'Invalid UUID', example: 'Invalid UUID', value: 'Invalid UUID'),
            ]
        )
    )]
    public function similarQuestions(Request $request, TermFrequencyRepository $repo)
    {
        $query = $request->query->get('query');

        if (!$query) {
            return $this->json('Query missing', 400);
        }

        $except = $request->query->all('exclude');
        $uuids = [];
        foreach ($except as $e) {
            if (!Uuid::isValid($e)) {
                return $this->json('Invalid UUID', 400);
            }
            array_push($uuids, new Uuid($e));
        }

        return $this->json($repo->getSimilarQuestions($query, $uuids), context: ['groups' => ['question:get_questions']]);
    }
}
