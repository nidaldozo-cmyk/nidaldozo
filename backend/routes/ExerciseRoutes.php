<?php

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
