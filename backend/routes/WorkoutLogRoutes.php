<?php

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
