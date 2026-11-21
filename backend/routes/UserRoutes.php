<?php

use OpenApi\Attributes as OA;

Flight::route('GET /users', function (): void {
    $userService = new UserService();
    Flight::json($userService->getAll(), 200);
});

Flight::route('GET /users/@id', function (string $id): void {
    $userService = new UserService();
    Flight::json($userService->getById((int) $id), 200);
});

Flight::route('POST /users', function (): void {
    $userService = new UserService();
    Flight::json($userService->create(getJsonBody()), 201);
});

Flight::route('PUT /users/@id', function (string $id): void {
    $userService = new UserService();
    Flight::json($userService->update((int) $id, getJsonBody()), 200);
});

Flight::route('PATCH /users/@id', function (string $id): void {
    $userService = new UserService();
    Flight::json($userService->update((int) $id, getJsonBody()), 200);
});

Flight::route('DELETE /users/@id', function (string $id): void {
    $userService = new UserService();
    $userService->delete((int) $id);
    Flight::json(['message' => 'User deleted successfully.'], 200);
});

final class UserRouteDocumentation
{
    #[OA\Get(
        path: '/users',
        operationId: 'listUsers',
        summary: 'List users.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Users retrieved.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/User')
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function listUsers(): void
    {
    }

    #[OA\Get(
        path: '/users/{id}',
        operationId: 'getUserById',
        summary: 'Get a user by ID.',
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
                description: 'User retrieved.',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 404,
                description: 'User not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function getUserById(): void
    {
    }

    #[OA\Post(
        path: '/users',
        operationId: 'createUser',
        summary: 'Create a user.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UserInput')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created.',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid user data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function createUser(): void
    {
    }

    #[OA\Put(
        path: '/users/{id}',
        operationId: 'replaceUser',
        summary: 'Update a user.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/UserInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid user data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'User not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function replaceUser(): void
    {
    }

    #[OA\Patch(
        path: '/users/{id}',
        operationId: 'updateUser',
        summary: 'Partially update a user.',
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
            content: new OA\JsonContent(ref: '#/components/schemas/UserInput')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated.',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid user data.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'User not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function updateUser(): void
    {
    }

    #[OA\Delete(
        path: '/users/{id}',
        operationId: 'deleteUser',
        summary: 'Delete a user.',
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
                description: 'User deleted.',
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
                description: 'User not found.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
        ]
    )]
    public function deleteUser(): void
    {
    }
}
