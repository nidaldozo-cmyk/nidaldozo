<?php

use OpenApi\Attributes as OA;

Flight::route('GET /exercises', function (): void {
    $exerciseService = new ExerciseService();
    if (array_key_exists('muscle_group', $_GET)) {
        Flight::json($exerciseService->getByMuscleGroup($_GET['muscle_group']), 200);
        return;
    }

    Flight::json($exerciseService->getAll(), 200);
});

Flight::route('GET /exercises/@id', function (string $id): void {
    $exerciseService = new ExerciseService();
    Flight::json($exerciseService->getById((int) $id), 200);
});

Flight::route('POST /exercises', function (): void {
    $exerciseService = new ExerciseService();
    Flight::json($exerciseService->create(getJsonBody()), 201);
});

Flight::route('PUT /exercises/@id', function (string $id): void {
    $exerciseService = new ExerciseService();
    Flight::json($exerciseService->update((int) $id, getJsonBody()), 200);
});

Flight::route('PATCH /exercises/@id', function (string $id): void {
    $exerciseService = new ExerciseService();
    Flight::json($exerciseService->update((int) $id, getJsonBody()), 200);
});

Flight::route('DELETE /exercises/@id', function (string $id): void {
    $exerciseService = new ExerciseService();
    $exerciseService->delete((int) $id);
    Flight::json(['message' => 'Exercise deleted successfully.'], 200);
});

final class ExerciseRouteDocumentation
{
    #[OA\Get(
        path: '/exercises',
        operationId: 'listExercises',
        summary: 'List exercises, optionally filtered by muscle group.',
        parameters: [
            new OA\Parameter(
                name: 'muscle_group',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Exercises retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Exercise')
                )
            ),
            new OA\Response(
                response: 400,
                description: 'The muscle_group parameter must not be empty.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listExercises(): void
    {
    }

    #[OA\Get(
        path: '/exercises/{id}',
        operationId: 'getExerciseById',
        summary: 'Get an exercise by ID.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Exercise retrieved.',
                content: new OA\JsonContent(ref: '#/components/schemas/Exercise')
            ),
            new OA\Response(
                response: 404,
                description: 'Exercise not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function getExerciseById(): void
    {
    }

    #[OA\Post(
        path: '/exercises',
        operationId: 'createExercise',
        summary: 'Create an exercise.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ExerciseInput')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Exercise created.',
                content: new OA\JsonContent(ref: '#/components/schemas/Exercise')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid exercise data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function createExercise(): void
    {
    }

    #[OA\Put(
        path: '/exercises/{id}',
        operationId: 'replaceExercise',
        summary: 'Update an exercise.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ExerciseInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Exercise updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/Exercise')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid exercise data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Exercise not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function replaceExercise(): void
    {
    }

    #[OA\Patch(
        path: '/exercises/{id}',
        operationId: 'updateExercise',
        summary: 'Partially update an exercise.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ExerciseInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Exercise updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/Exercise')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid exercise data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Exercise not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function updateExercise(): void
    {
    }

    #[OA\Delete(
        path: '/exercises/{id}',
        operationId: 'deleteExercise',
        summary: 'Delete an exercise.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Exercise deleted.',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                    ],
                    required: ['message']
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Exercise is referenced by workout logs or goals.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Exercise not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function deleteExercise(): void
    {
    }
}
