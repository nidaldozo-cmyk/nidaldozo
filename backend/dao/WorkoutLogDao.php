<?php

require_once __DIR__ . '/BaseDao.php';

class WorkoutLogDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('workout_logs', [
            'workout_id',
            'exercise_id',
            'sets',
            'reps',
            'weight',
        ]);
    }

    public function getByWorkoutIdForUser(int $workoutId, int $userId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT workout_logs.* FROM workout_logs
             INNER JOIN workouts ON workouts.id = workout_logs.workout_id
             WHERE workout_logs.workout_id = :workout_id
               AND workouts.user_id = :user_id
             ORDER BY workout_logs.id ASC'
        );
        $stmt->execute([
            'workout_id' => $workoutId,
            'user_id' => $userId,
        ]);

        return $stmt->fetchAll();
    }
}
