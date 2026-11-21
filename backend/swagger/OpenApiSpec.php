<?php

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        title: 'FitPulse API',
        version: '1.0.0'
    ),
    servers: [
        new OA\Server(url: 'http://localhost/nidaldozo/backend'),
    ],
    components: new OA\Components(
        schemas: [
            new OA\Schema(
                schema: 'User',
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'name', type: 'string', maxLength: 100),
                    new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 254),
                    new OA\Property(property: 'role', type: 'string', enum: ['user', 'admin']),
                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                ],
                required: ['id', 'name', 'email', 'role', 'created_at']
            ),
            new OA\Schema(
                schema: 'UserInput',
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 100),
                    new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 254),
                    new OA\Property(property: 'password', type: 'string', minLength: 8),
                    new OA\Property(property: 'role', type: 'string', enum: ['user', 'admin']),
                ],
                required: ['name', 'email', 'password']
            ),
            new OA\Schema(
                schema: 'Workout',
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'user_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'title', type: 'string', maxLength: 150),
                    new OA\Property(property: 'workout_date', type: 'string', format: 'date'),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ],
                required: ['id', 'user_id', 'title', 'workout_date']
            ),
            new OA\Schema(
                schema: 'WorkoutInput',
                type: 'object',
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'title', type: 'string', maxLength: 150),
                    new OA\Property(property: 'workout_date', type: 'string', format: 'date'),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ],
                required: ['user_id', 'title', 'workout_date']
            ),
            new OA\Schema(
                schema: 'Exercise',
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'name', type: 'string', maxLength: 150),
                    new OA\Property(property: 'muscle_group', type: 'string', maxLength: 100),
                    new OA\Property(property: 'equipment', type: 'string', nullable: true, maxLength: 100),
                    new OA\Property(property: 'external_api_id', type: 'string', nullable: true, maxLength: 100),
                ],
                required: ['id', 'name', 'muscle_group']
            ),
            new OA\Schema(
                schema: 'ExerciseInput',
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 150),
                    new OA\Property(property: 'muscle_group', type: 'string', maxLength: 100),
                    new OA\Property(property: 'equipment', type: 'string', nullable: true, maxLength: 100),
                    new OA\Property(property: 'external_api_id', type: 'string', nullable: true, maxLength: 100),
                ],
                required: ['name', 'muscle_group']
            ),
            new OA\Schema(
                schema: 'WorkoutLog',
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'workout_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'exercise_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'sets', type: 'integer', format: 'int32', minimum: 1),
                    new OA\Property(property: 'reps', type: 'integer', format: 'int32', minimum: 1),
                    new OA\Property(property: 'weight', type: 'number', format: 'double', nullable: true, minimum: 0),
                ],
                required: ['id', 'workout_id', 'exercise_id', 'sets', 'reps']
            ),
            new OA\Schema(
                schema: 'WorkoutLogInput',
                type: 'object',
                properties: [
                    new OA\Property(property: 'workout_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'exercise_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'sets', type: 'integer', format: 'int32', minimum: 1),
                    new OA\Property(property: 'reps', type: 'integer', format: 'int32', minimum: 1),
                    new OA\Property(property: 'weight', type: 'number', format: 'double', nullable: true, minimum: 0),
                ],
                required: ['workout_id', 'exercise_id', 'sets', 'reps']
            ),
            new OA\Schema(
                schema: 'Goal',
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'user_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'exercise_id', type: 'integer', format: 'int64', nullable: true, minimum: 1),
                    new OA\Property(property: 'goal_type', type: 'string', maxLength: 100),
                    new OA\Property(property: 'target_value', type: 'number', format: 'double', minimum: 0),
                    new OA\Property(property: 'current_value', type: 'number', format: 'double', minimum: 0),
                    new OA\Property(property: 'deadline', type: 'string', format: 'date', nullable: true),
                ],
                required: ['id', 'user_id', 'goal_type', 'target_value', 'current_value']
            ),
            new OA\Schema(
                schema: 'GoalInput',
                type: 'object',
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', format: 'int64', minimum: 1),
                    new OA\Property(property: 'exercise_id', type: 'integer', format: 'int64', nullable: true, minimum: 1),
                    new OA\Property(property: 'goal_type', type: 'string', maxLength: 100),
                    new OA\Property(property: 'target_value', type: 'number', format: 'double', minimum: 0),
                    new OA\Property(property: 'current_value', type: 'number', format: 'double', minimum: 0),
                    new OA\Property(property: 'deadline', type: 'string', format: 'date', nullable: true),
                ],
                required: ['user_id', 'goal_type', 'target_value']
            ),
            new OA\Schema(
                schema: 'Error',
                type: 'object',
                properties: [
                    new OA\Property(property: 'error', type: 'string'),
                ],
                required: ['error']
            ),
        ]
    )
)]
final class OpenApiSpec
{
}
