<?php
namespace App\Service;

use App\Model\User;

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

    public function fetchByEmail($email): ?User
    {
        return null;
    }

    public function fetchByCredentials(string $name, string $password): ?User
    {
        return null;
    }

    public function fetch(): array
    {
        return [];
    }
}
