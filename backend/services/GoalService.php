<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/GoalDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/ExerciseDao.php';

class GoalService extends BaseService
{
    private GoalDao $goalDao;
    private UserDao $userDao;
    private ExerciseDao $exerciseDao;

    public function __construct(
        ?GoalDao $goalDao = null,
        ?UserDao $userDao = null,
        ?ExerciseDao $exerciseDao = null
    ) {
        $this->goalDao = $goalDao ?? new GoalDao();
        $this->userDao = $userDao ?? new UserDao();
        $this->exerciseDao = $exerciseDao ?? new ExerciseDao();
        parent::__construct($this->goalDao);
    }

    public function getByUserId(int $userId): array
    {
        return $this->goalDao->getByUserId($userId);
    }

    protected function validate(array $data, bool $isUpdate): void
    {
        if (!$isUpdate || array_key_exists('user_id', $data)) {
            $userId = $this->positiveInteger($data['user_id'] ?? null);
            if ($userId === null || $this->userDao->getById($userId) === null) {
                throw new Exception('A valid existing user_id is required.', 400);
            }
        }

        if (!$isUpdate || array_key_exists('goal_type', $data)) {
            if (!isset($data['goal_type']) || !is_string($data['goal_type']) || trim($data['goal_type']) === '') {
                throw new Exception('Goal type is required.', 400);
            }
        }

        if (!$isUpdate || array_key_exists('target_value', $data)) {
            if (
                !array_key_exists('target_value', $data)
                || !is_numeric($data['target_value'])
                || !is_finite((float) $data['target_value'])
                || (float) $data['target_value'] <= 0
            ) {
                throw new Exception('Target value must be greater than 0.', 400);
            }
        }

        if (array_key_exists('exercise_id', $data) && $data['exercise_id'] !== null) {
            $exerciseId = $this->positiveInteger($data['exercise_id']);
            if ($exerciseId === null || $this->exerciseDao->getById($exerciseId) === null) {
                throw new Exception('Exercise does not exist.', 400);
            }
        }

        if (array_key_exists('current_value', $data)) {
            if (
                !is_numeric($data['current_value'])
                || !is_finite((float) $data['current_value'])
                || (float) $data['current_value'] < 0
            ) {
                throw new Exception('Current value must not be negative.', 400);
            }
        }

        if (array_key_exists('deadline', $data) && $data['deadline'] !== null) {
            $deadline = $data['deadline'];
            if (!is_string($deadline)) {
                throw new Exception('Deadline must be a valid Y-m-d date.', 400);
            }

            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $deadline);
            $dateErrors = DateTimeImmutable::getLastErrors();
            if (
                $date === false
                || $date->format('Y-m-d') !== $deadline
                || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
            ) {
                throw new Exception('Deadline must be a valid Y-m-d date.', 400);
            }

            if (!$isUpdate && $date < new DateTimeImmutable('today')) {
                throw new Exception('Deadline cannot be in the past.', 400);
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
