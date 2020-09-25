<?php
namespace App\Service;

abstract class AbstractAuthor extends Author
{
    public function __construct()
    {

    }
    public function get(string $id): \stdClass
    {
        return new \stdClass;
    }

    public function fetch(string $id)
    {

    }

    public function fetchList(): array
    {
        return [];
    }

    public function fetchAffected(): array
    {
        return [];
    }

    public function save(array $data): int
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
