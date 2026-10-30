<?php

class BaseDao
{
    protected PDO $connection;
    protected string $table;
    protected array $columns;   // columns allowed in insert/update

    public function __construct(string $table, array $columns)
    {
        $this->table = $table;
        $this->columns = $columns;

        $config = require __DIR__ . '/../config/config.php';
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";

        $this->connection = new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    public function getAll(): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insert(array $data): int
    {
        $data = $this->filterColumns($data);
        $names = array_keys($data);
        $columns = implode(', ', $names);
        $placeholders = ':' . implode(', :', $names);

        $stmt = $this->connection->prepare(
            "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)"
        );
        $stmt->execute($data);
        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $data = $this->filterColumns($data);
        $assignments = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($data)));

        $stmt = $this->connection->prepare(
            "UPDATE {$this->table} SET $assignments WHERE id = :id"
        );
        $stmt->execute($data + ['id' => $id]);
        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    // Keeps only the columns the child DAO allows, so unknown keys can never
    // reach the SQL text (column names cannot be bound as parameters).
    private function filterColumns(array $data): array
    {
        $data = array_intersect_key($data, array_flip($this->columns));
        if (empty($data)) {
            throw new InvalidArgumentException('No valid columns provided.');
        }
        return $data;
    }
}