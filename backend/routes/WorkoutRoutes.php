<?php

use OpenApi\Attributes as OA;

Flight::route('GET /workouts', function (): void {
    $workoutService = new WorkoutService();
    Flight::json($workoutService->getAll(), 200);
});

Flight::route('GET /workouts/@id', function (string $id): void {
    $workoutService = new WorkoutService();
    Flight::json($workoutService->getById((int) $id), 200);
});

Flight::route('POST /workouts', function (): void {
    $workoutService = new WorkoutService();
    Flight::json($workoutService->create(getJsonBody()), 201);
});

Flight::route('PUT /workouts/@id', function (string $id): void {
    $workoutService = new WorkoutService();
    Flight::json($workoutService->update((int) $id, getJsonBody()), 200);
});

Flight::route('PATCH /workouts/@id', function (string $id): void {
    $workoutService = new WorkoutService();
    Flight::json($workoutService->update((int) $id, getJsonBody()), 200);
});

Flight::route('DELETE /workouts/@id', function (string $id): void {
    $workoutService = new WorkoutService();
    $workoutService->delete((int) $id);
    Flight::json(['message' => 'Workout deleted successfully.'], 200);
});

Flight::route('GET /users/@id/workouts', function (string $id): void {
    $workoutService = new WorkoutService();
    Flight::json($workoutService->getByUserId((int) $id), 200);
});

final class WorkoutRouteDocumentation
{
    #[OA\Get(
        path: '/workouts',
        operationId: 'listWorkouts',
        summary: 'List workouts.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Workouts retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Workout')
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listWorkouts(): void
    {
    }

    #[OA\Get(
        path: '/workouts/{id}',
        operationId: 'getWorkoutById',
        summary: 'Get a workout by ID.',
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
                description: 'Workout retrieved.',
                content: new OA\JsonContent(ref: '#/components/schemas/Workout')
            ),
            new OA\Response(
                response: 404,
                description: 'Workout not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function getWorkoutById(): void
    {
    }

    #[OA\Post(
        path: '/workouts',
        operationId: 'createWorkout',
        summary: 'Create a workout.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/WorkoutInput')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Workout created.',
                content: new OA\JsonContent(ref: '#/components/schemas/Workout')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid workout data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function createWorkout(): void
    {
    }

    #[OA\Put(
        path: '/workouts/{id}',
        operationId: 'replaceWorkout',
        summary: 'Update a workout.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/WorkoutInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Workout updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/Workout')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request body.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Workout not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function replaceWorkout(): void
    {
    }

    #[OA\Patch(
        path: '/workouts/{id}',
        operationId: 'updateWorkout',
        summary: 'Partially update a workout.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/WorkoutInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Workout updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/Workout')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request body.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Workout not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function updateWorkout(): void
    {
    }

    #[OA\Delete(
        path: '/workouts/{id}',
        operationId: 'deleteWorkout',
        summary: 'Delete a workout.',
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
                description: 'Workout deleted.',
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
                description: 'Workout not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function deleteWorkout(): void
    {
    }

    #[OA\Get(
        path: '/users/{id}/workouts',
        operationId: 'listWorkoutsByUser',
        summary: 'List workouts for a user.',
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
                description: 'Workouts for the user retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Workout')
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listWorkoutsByUser(): void
    {
    }
}
