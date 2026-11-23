<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/WorkoutDao.php';
require_once __DIR__ . '/../dao/UserDao.php';

class WorkoutService extends BaseService
{
    private WorkoutDao $workoutDao;
    private UserDao $userDao;

    public function __construct(?WorkoutDao $workoutDao = null, ?UserDao $userDao = null)
    {
        $this->workoutDao = $workoutDao ?? new WorkoutDao();
        $this->userDao = $userDao ?? new UserDao();
        parent::__construct($this->workoutDao);
    }

    public function getByUserId(int $userId): array
    {
        return $this->workoutDao->getByUserId($userId);
    }

    protected function validate(array $data, bool $isUpdate): void
    {
        if ($isUpdate) {
            return;
        }

        $userId = $data['user_id'] ?? null;
        if (!is_int($userId) || $userId < 1 || $this->userDao->getById($userId) === null) {
            throw new Exception('A valid existing user_id is required.', 400);
        }

        $title = $data['title'] ?? null;
        if (!is_string($title) || trim($title) === '') {
            throw new Exception('Title is required.', 400);
        }
        $titleLength = preg_match_all('/./us', $title);
        if ($titleLength === false || $titleLength > 150) {
            throw new Exception('Title must be at most 150 characters.', 400);
        }

        $workoutDate = $data['workout_date'] ?? null;
        if (!is_string($workoutDate)) {
            throw new Exception('A valid workout_date in Y-m-d format is required.', 400);
        }
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $workoutDate);
        $dateErrors = DateTimeImmutable::getLastErrors();
        if (
            $date === false
            || $date->format('Y-m-d') !== $workoutDate
            || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
        ) {
            throw new Exception('A valid workout_date in Y-m-d format is required.', 400);
        }
    }
}
