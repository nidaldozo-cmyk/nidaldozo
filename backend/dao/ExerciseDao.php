<?php

require_once __DIR__ . '/BaseDao.php';

class ExerciseDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('exercises', [
            'name',
            'muscle_group',
            'equipment',
            'external_api_id',
        ]);
    }

    public function search(string $searchTerm = ''): array
    {
        $sql = 'SELECT * FROM exercises';
        $params = [];

        if ($searchTerm !== '') {
            $sql .= ' WHERE name LIKE :name OR muscle_group LIKE :muscle_group';
            $searchPattern = '%' . $searchTerm . '%';
            $params = [
                'name' => $searchPattern,
                'muscle_group' => $searchPattern,
            ];
        }

        $sql .= ' ORDER BY name ASC, id ASC';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
