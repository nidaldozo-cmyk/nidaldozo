<?php

use OpenApi\Attributes as OA;

Flight::route('GET /goals', function (): void {
    $goalService = new GoalService();
    Flight::json($goalService->getAll(), 200);
});

Flight::route('GET /goals/@id', function (string $id): void {
    $goalService = new GoalService();
    Flight::json($goalService->getById((int) $id), 200);
});

Flight::route('POST /goals', function (): void {
    $goalService = new GoalService();
    Flight::json($goalService->create(getJsonBody()), 201);
});

Flight::route('PUT /goals/@id', function (string $id): void {
    $goalService = new GoalService();
    Flight::json($goalService->update((int) $id, getJsonBody()), 200);
});

Flight::route('PATCH /goals/@id', function (string $id): void {
    $goalService = new GoalService();
    Flight::json($goalService->update((int) $id, getJsonBody()), 200);
});

Flight::route('DELETE /goals/@id', function (string $id): void {
    $goalService = new GoalService();
    $goalService->delete((int) $id);
    Flight::json(['message' => 'Goal deleted successfully.'], 200);
});

Flight::route('GET /users/@id/goals', function (string $id): void {
    $goalService = new GoalService();
    Flight::json($goalService->getByUserId((int) $id), 200);
});

final class GoalRouteDocumentation
{
    #[OA\Get(
        path: '/goals',
        operationId: 'listGoals',
        summary: 'List goals.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Goals retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Goal')
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listGoals(): void
    {
    }

    #[OA\Get(
        path: '/goals/{id}',
        operationId: 'getGoalById',
        summary: 'Get a goal by ID.',
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
                description: 'Goal retrieved.',
                content: new OA\JsonContent(ref: '#/components/schemas/Goal')
            ),
            new OA\Response(
                response: 404,
                description: 'Goal not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function getGoalById(): void
    {
    }

    #[OA\Post(
        path: '/goals',
        operationId: 'createGoal',
        summary: 'Create a goal.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/GoalInput')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Goal created.',
                content: new OA\JsonContent(ref: '#/components/schemas/Goal')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid goal data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function createGoal(): void
    {
    }

    #[OA\Put(
        path: '/goals/{id}',
        operationId: 'replaceGoal',
        summary: 'Update a goal.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/GoalInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Goal updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/Goal')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid goal data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Goal not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function replaceGoal(): void
    {
    }

    #[OA\Patch(
        path: '/goals/{id}',
        operationId: 'updateGoal',
        summary: 'Partially update a goal.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/GoalInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Goal updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/Goal')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid goal data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Goal not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function updateGoal(): void
    {
    }

    #[OA\Delete(
        path: '/goals/{id}',
        operationId: 'deleteGoal',
        summary: 'Delete a goal.',
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
                description: 'Goal deleted.',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                    ],
                    required: ['message']
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Goal not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function deleteGoal(): void
    {
    }

    #[OA\Get(
        path: '/users/{id}/goals',
        operationId: 'listGoalsByUser',
        summary: 'List goals for a user.',
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
                description: 'Goals for the user retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Goal')
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listGoalsByUser(): void
    {
    }
}
