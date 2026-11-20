<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ExerciseDao.php';
require_once __DIR__ . '/../dao/WorkoutLogDao.php';
require_once __DIR__ . '/../dao/GoalDao.php';

class ExerciseService extends BaseService
{
    private ExerciseDao $exerciseDao;
    private WorkoutLogDao $workoutLogDao;
    private GoalDao $goalDao;
    private ?int $updatingExerciseId = null;

    public function __construct(
        ?ExerciseDao $exerciseDao = null,
        ?WorkoutLogDao $workoutLogDao = null,
        ?GoalDao $goalDao = null
    ) {
        $this->exerciseDao = $exerciseDao ?? new ExerciseDao();
        $this->workoutLogDao = $workoutLogDao ?? new WorkoutLogDao();
        $this->goalDao = $goalDao ?? new GoalDao();
        parent::__construct($this->exerciseDao);
    }

    public function update(int $id, array $data): array
    {
        $this->updatingExerciseId = $id;
        try {
            return parent::update($id, $data);
        } finally {
            $this->updatingExerciseId = null;
        }
    }

    public function delete(int $id): int
    {
        if ($this->workoutLogDao->hasExerciseLogs($id) || $this->hasGoals($id)) {
            throw new Exception(
                'Cannot delete this exercise because it is used by workout logs or goals.',
                400
            );
        }

        try {
            return parent::delete($id);
        } catch (PDOException $exception) {
            if (($exception->errorInfo[1] ?? null) === 1451) {
                throw new Exception(
                    'Cannot delete this exercise because it is still in use.',
                    400,
                    $exception
                );
            }

            throw $exception;
        }
    }

    protected function validate(array $data, bool $isUpdate): void
    {
        foreach (['name' => 150, 'muscle_group' => 100] as $field => $maxLength) {
            if (!$isUpdate || array_key_exists($field, $data)) {
                $value = $data[$field] ?? null;
                $length = is_string($value) ? preg_match_all('/./us', $value) : false;
                if ($length === false || trim($value) === '') {
                    throw new Exception(ucfirst(str_replace('_', ' ', $field)) . ' is required.', 400);
                }
                if ($length > $maxLength) {
                    throw new Exception(
                        ucfirst(str_replace('_', ' ', $field)) . " must be at most {$maxLength} characters.",
                        400
                    );
                }
            }
        }

        foreach (['equipment', 'external_api_id'] as $field) {
            if (
                array_key_exists($field, $data)
                && $data[$field] !== null
                && !is_string($data[$field])
            ) {
                throw new Exception(ucfirst(str_replace('_', ' ', $field)) . ' must be a string or null.', 400);
            }
        }

        $existingExercise = null;
        if ($isUpdate) {
            $existingExercise = $this->exerciseDao->getById($this->updatingExerciseId);
        }

        $name = $data['name'] ?? $existingExercise['name'] ?? null;
        $muscleGroup = $data['muscle_group'] ?? $existingExercise['muscle_group'] ?? null;
        if (
            is_string($name)
            && is_string($muscleGroup)
            && $this->exerciseDao->existsByNameAndMuscleGroup($name, $muscleGroup)
        ) {
            foreach ($this->exerciseDao->getAll() as $exercise) {
                if (
                    $exercise['name'] === $name
                    && $exercise['muscle_group'] === $muscleGroup
                    && (int) $exercise['id'] !== $this->updatingExerciseId
                ) {
                    throw new Exception(
                        'An exercise with this name and muscle group already exists.',
                        400
                    );
                }
            }
        }
    }

    private function hasGoals(int $exerciseId): bool
    {
        foreach ($this->goalDao->getAll() as $goal) {
            if ((int) ($goal['exercise_id'] ?? 0) === $exerciseId) {
                return true;
            }
        }

        return false;
    }
}
