<?php

require_once __DIR__ . '/../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/services/BaseService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/WorkoutService.php';
require_once __DIR__ . '/services/ExerciseService.php';
require_once __DIR__ . '/services/WorkoutLogService.php';
require_once __DIR__ . '/services/GoalService.php';

foreach (['UserRoutes.php', 'WorkoutRoutes.php', 'ExerciseRoutes.php', 'WorkoutLogRoutes.php', 'GoalRoutes.php'] as $routeFile) {
    $routePath = __DIR__ . '/routes/' . $routeFile;
    if (is_file($routePath)) {
        require_once $routePath;
    }
}

function getJsonBody(): array
{
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        throw new Exception('Request body must be valid JSON.', 400);
    }

    return $data;
}

Flight::map('error', function (Throwable $exception): void {
    $status = $exception->getCode();
    if (!is_int($status) || $status < 400 || $status > 599) {
        $status = 500;
    }

    $message = $status >= 500 ? 'Internal server error' : $exception->getMessage();
    Flight::json(['error' => $message], $status);
    Flight::response()->send();
});

Flight::map('notFound', function (): void {
    Flight::json(['error' => 'Not found'], 404);
});

Flight::set('flight.base_url', '/nidaldozo/backend');

Flight::start();
