<?php

require_once __DIR__ . '/BaseDao.php';

class GoalDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('goals', [
            'user_id',
            'exercise_id',
            'goal_type',
            'target_value',
            'current_value',
            'deadline',
        ]);
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM goals
             WHERE user_id = :user_id
             ORDER BY deadline ASC, id ASC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getByIdForUser(int $id, int $userId): ?array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM goals WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
