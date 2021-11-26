<?php
namespace App\Service;

use App\Model;

abstract class AbstractUser extends UserService
{
    public function __construct()
    {
    }

    public function save(array $data): int
    {
        return 0;
    }

    public function delete(string $id): int
    {
        return 0;
    }

    public function fetchByEmail($email): ?Model\User
    {
        return null;
    }

    public function fetchByCredentials($name, $password): ?Model\User
    {
        return null;
    }

    public function fetch(): array
    {
        return [];
    }
}
