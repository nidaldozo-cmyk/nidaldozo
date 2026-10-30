<?php

require_once __DIR__ . '/BaseDao.php';

class WorkoutDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('workouts', ['user_id', 'title', 'workout_date', 'notes']);
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM workouts WHERE user_id = :user_id ORDER BY workout_date DESC, id DESC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getByIdForUser(int $id, int $userId): ?array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM workouts WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
