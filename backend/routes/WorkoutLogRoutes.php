<?php

use OpenApi\Attributes as OA;

Flight::route('GET /workout-logs', function (): void {
    $workoutLogService = new WorkoutLogService();
    Flight::json($workoutLogService->getAll(), 200);
});

Flight::route('GET /workout-logs/@id', function (string $id): void {
    $workoutLogService = new WorkoutLogService();
    Flight::json($workoutLogService->getById((int) $id), 200);
});

Flight::route('POST /workout-logs', function (): void {
    $workoutLogService = new WorkoutLogService();
    Flight::json($workoutLogService->create(getJsonBody()), 201);
});

Flight::route('PUT /workout-logs/@id', function (string $id): void {
    $workoutLogService = new WorkoutLogService();
    Flight::json($workoutLogService->update((int) $id, getJsonBody()), 200);
});

Flight::route('PATCH /workout-logs/@id', function (string $id): void {
    $workoutLogService = new WorkoutLogService();
    Flight::json($workoutLogService->update((int) $id, getJsonBody()), 200);
});

Flight::route('DELETE /workout-logs/@id', function (string $id): void {
    $workoutLogService = new WorkoutLogService();
    $workoutLogService->delete((int) $id);
    Flight::json(['message' => 'Workout log deleted successfully.'], 200);
});

Flight::route('GET /workouts/@id/logs', function (string $id): void {
    $workoutLogService = new WorkoutLogService();
    Flight::json($workoutLogService->getByWorkoutId((int) $id), 200);
});

final class WorkoutLogRouteDocumentation
{
    #[OA\Get(
        path: '/workout-logs',
        operationId: 'listWorkoutLogs',
        summary: 'List workout logs.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Workout logs retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/WorkoutLog')
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listWorkoutLogs(): void
    {
    }

    #[OA\Get(
        path: '/workout-logs/{id}',
        operationId: 'getWorkoutLogById',
        summary: 'Get a workout log by ID.',
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
                description: 'Workout log retrieved.',
                content: new OA\JsonContent(ref: '#/components/schemas/WorkoutLog')
            ),
            new OA\Response(
                response: 404,
                description: 'Workout log not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function getWorkoutLogById(): void
    {
    }

    #[OA\Post(
        path: '/workout-logs',
        operationId: 'createWorkoutLog',
        summary: 'Create a workout log.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/WorkoutLogInput')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Workout log created.',
                content: new OA\JsonContent(ref: '#/components/schemas/WorkoutLog')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid workout log data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function createWorkoutLog(): void
    {
    }

    #[OA\Put(
        path: '/workout-logs/{id}',
        operationId: 'replaceWorkoutLog',
        summary: 'Update a workout log.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/WorkoutLogInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Workout log updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/WorkoutLog')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request body.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Workout log not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function replaceWorkoutLog(): void
    {
    }

    #[OA\Patch(
        path: '/workout-logs/{id}',
        operationId: 'updateWorkoutLog',
        summary: 'Partially update a workout log.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/WorkoutLogInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Workout log updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/WorkoutLog')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request body.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Workout log not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function updateWorkoutLog(): void
    {
    }

    #[OA\Delete(
        path: '/workout-logs/{id}',
        operationId: 'deleteWorkoutLog',
        summary: 'Delete a workout log.',
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
                description: 'Workout log deleted.',
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
                description: 'Workout log not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function deleteWorkoutLog(): void
    {
    }

    #[OA\Get(
        path: '/workouts/{id}/logs',
        operationId: 'listWorkoutLogsByWorkout',
        summary: 'List workout logs for a workout.',
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
                description: 'Workout logs for the workout retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/WorkoutLog')
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listWorkoutLogsByWorkout(): void
    {
    }
}
