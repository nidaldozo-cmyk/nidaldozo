<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService
{
    private UserDao $userDao;
    private ?int $updatingUserId = null;

    public function __construct(?UserDao $userDao = null)
    {
        $this->userDao = $userDao ?? new UserDao();
        parent::__construct($this->userDao);
    }

    public function getAll(): array
    {
        return array_map(fn(array $user): array => $this->withoutPasswordHash($user), parent::getAll());
    }

    public function getById(int $id): array
    {
        return $this->withoutPasswordHash(parent::getById($id));
    }

    public function create(array $data): array
    {
        $data = $this->prepareData($data);
        $this->validate($data, false);

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);

        $id = $this->userDao->insert($data);

        return $this->getById($id);
    }

    public function update(int $id, array $data): array
    {
        $this->getById($id);
        $data = $this->prepareData($data);
        if ($data === []) {
            throw new Exception('At least one valid field must be provided.', 400);
        }

        $this->updatingUserId = $id;
        try {
            $this->validate($data, true);
        } finally {
            $this->updatingUserId = null;
        }

        if (array_key_exists('password', $data)) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }

        $this->userDao->update($id, $data);

        return $this->getById($id);
    }

    protected function validate(array $data, bool $isUpdate): void
    {
        if (!$isUpdate || array_key_exists('name', $data)) {
            if (!isset($data['name']) || !is_string($data['name']) || trim($data['name']) === '') {
                throw new Exception('Name is required.', 400);
            }
        }

        if (!$isUpdate || array_key_exists('email', $data)) {
            if (
                !isset($data['email'])
                || !is_string($data['email'])
                || filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false
            ) {
                throw new Exception('A valid email address is required.', 400);
            }

            $existingUser = $this->userDao->getByEmail($data['email']);
            if (
                $existingUser !== null
                && (!$isUpdate || (int) ($existingUser['id'] ?? 0) !== $this->updatingUserId)
            ) {
                throw new Exception('Email address is already in use.', 400);
            }
        }

        if (!$isUpdate || array_key_exists('password', $data)) {
            $password = $data['password'] ?? null;
            $passwordLength = is_string($password) ? preg_match_all('/./us', $password) : false;
            if ($passwordLength === false || $passwordLength < 8) {
                throw new Exception('Password must be at least 8 characters.', 400);
            }
        }

        if (
            array_key_exists('role', $data)
            && (!is_string($data['role']) || !in_array($data['role'], ['user', 'admin'], true))
        ) {
            throw new Exception('Role must be user or admin.', 400);
        }
    }

    private function prepareData(array $data): array
    {
        unset($data['password_hash']);
        $data = array_intersect_key($data, array_flip(['name', 'email', 'password', 'role']));

        foreach (['name', 'email'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = trim($data[$field]);
            }
        }

        return $data;
    }

    private function withoutPasswordHash(array $user): array
    {
        unset($user['password_hash'], $user['password']);

        return $user;
    }
}
