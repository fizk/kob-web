<?php
namespace App\Service;

use App\Model;

abstract class AbstractAuthor extends AuthorService
{
    public function __construct()
    {

    }
    public function get(string $id): ?Model\Author
    {
        return null;
    }

    public function fetch(string $id): ?Model\Author
    {
        return null;
    }

    public function fetchList(): array
    {
        return [];
    }

    public function fetchAffected(): array
    {
        return [];
    }

    public function save(Model\Author $data): int
    {
        return 0;
    }

    public function search($query): array
    {
        return [];
    }

    public function delete(string $id): int
    {
        return 0;
    }
}
