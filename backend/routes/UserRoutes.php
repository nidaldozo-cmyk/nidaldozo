<?php

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
