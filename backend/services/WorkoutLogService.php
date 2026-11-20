<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/WorkoutLogDao.php';
require_once __DIR__ . '/../dao/WorkoutDao.php';
require_once __DIR__ . '/../dao/ExerciseDao.php';

class WorkoutLogService extends BaseService
{
    private WorkoutLogDao $workoutLogDao;
    private WorkoutDao $workoutDao;
    private ExerciseDao $exerciseDao;

    public function __construct(
        ?WorkoutLogDao $workoutLogDao = null,
        ?WorkoutDao $workoutDao = null,
        ?ExerciseDao $exerciseDao = null
    ) {
        $this->workoutLogDao = $workoutLogDao ?? new WorkoutLogDao();
        $this->workoutDao = $workoutDao ?? new WorkoutDao();
        $this->exerciseDao = $exerciseDao ?? new ExerciseDao();
        parent::__construct($this->workoutLogDao);
    }

    public function getByWorkoutId(int $workoutId): array
    {
        return array_values(array_filter(
            $this->workoutLogDao->getAll(),
            fn(array $log): bool => (int) $log['workout_id'] === $workoutId
        ));
    }

    protected function validate(array $data, bool $isUpdate): void
    {
        if ($isUpdate) {
            return;
        }

        $workoutId = $this->positiveInteger($data['workout_id'] ?? null);
        if ($workoutId === null || $this->workoutDao->getById($workoutId) === null) {
            throw new Exception('A valid existing workout_id is required.', 400);
        }

        $exerciseId = $this->positiveInteger($data['exercise_id'] ?? null);
        if ($exerciseId === null || $this->exerciseDao->getById($exerciseId) === null) {
            throw new Exception('A valid existing exercise_id is required.', 400);
        }

        foreach (['sets', 'reps'] as $field) {
            if ($this->positiveInteger($data[$field] ?? null) === null) {
                throw new Exception(ucfirst($field) . ' must be a whole number greater than 0.', 400);
            }
        }

        if (array_key_exists('weight', $data) && $data['weight'] !== null) {
            if (!is_int($data['weight']) && !is_float($data['weight']) && !is_string($data['weight'])) {
                throw new Exception('Weight must be a non-negative number.', 400);
            }
            if (
                !is_numeric($data['weight'])
                || !is_finite((float) $data['weight'])
                || (float) $data['weight'] < 0
            ) {
                throw new Exception('Weight must be a non-negative number.', 400);
            }
        }
    }

    private function positiveInteger($value): ?int
    {
        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (!is_string($value) || preg_match('/^[0-9]+$/D', $value) !== 1) {
            return null;
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT);

        return $integer !== false && $integer > 0 ? $integer : null;
    }
}
