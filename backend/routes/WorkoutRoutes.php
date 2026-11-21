<?php

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
