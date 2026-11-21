<?php

use OpenApi\Attributes as OA;

Flight::route('GET /external/exercises', function (): void {
    $search = Flight::request()->query['search'] ?? null;
    if (!is_string($search)) {
        throw new Exception("Query parameter 'search' is required (minimum 2 characters).", 400);
    }

    $search = trim($search);
    $searchLength = preg_match_all('/./us', $search);
    if ($search === '' || $searchLength === false || $searchLength < 2) {
        throw new Exception("Query parameter 'search' is required (minimum 2 characters).", 400);
    }

    $config = require __DIR__ . '/../config/config.php';
    $externalExerciseService = new ExternalExerciseService($config['external_api']);
    $results = $externalExerciseService->searchExercises($search);

    Flight::json($results, 200);
});

final class ExternalExerciseRouteDocumentation
{
    #[OA\Get(
        path: '/external/exercises',
        operationId: 'searchExternalExercises',
        tags: ['External Exercises'],
        summary: 'Search exercises from the external exercise API.',
        parameters: [
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', minLength: 2, example: 'bench')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'External exercises retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'external_api_id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'muscle_group', type: 'string', nullable: true),
                            new OA\Property(property: 'equipment', type: 'string', nullable: true),
                            new OA\Property(property: 'image', type: 'string', nullable: true),
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Missing or invalid search parameter.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 502,
                description: 'Upstream API unreachable, returned a non-200 status, or returned an unexpected payload.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 504,
                description: 'Upstream API request timed out.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function searchExternalExercises(): void
    {
    }

}
