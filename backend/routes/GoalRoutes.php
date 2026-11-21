<?php

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
