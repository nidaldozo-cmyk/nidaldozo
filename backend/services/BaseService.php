<?php

class BaseService
{
    protected object $dao;

    public function __construct(object $dao)
    {
        $this->dao = $dao;
    }

    public function getAll(): array
    {
        return $this->dao->getAll();
    }

    public function getById(int $id): array
    {
        $row = $this->dao->getById($id);
        if ($row === null) {
            throw new Exception('Resource not found.', 404);
        }

        return $row;
    }

    public function create(array $data): array
    {
        $this->validate($data, false);
        $id = $this->dao->insert($data);

        return $this->getById($id);
    }

    public function update(int $id, array $data): array
    {
        $this->getById($id);
        $this->validate($data, true);
        $this->dao->update($id, $data);

        return $this->getById($id);
    }

    public function delete(int $id): int
    {
        $this->getById($id);

        return $this->dao->delete($id);
    }

    protected function validate(array $data, bool $isUpdate): void
    {
    }
}
