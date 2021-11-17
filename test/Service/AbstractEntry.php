<?php

namespace App\Service;


use DateTime;

abstract class AbstractEntry extends Entry
{
    public function __construct()
    {
    }

    public function get(string $id): \stdClass
    {
        return new \stdClass;
    }

    public function fetch(string $id, $lang = 'is'): ?array
    {
        return [];
    }

    public function fetchCurrent(DateTime $date, $language = 'is'): array
    {
        return [];
    }

    public function fetchLatestByType($type = 'show', $language = 'is'): array
    {
        return [];
    }

    public function fetchList(?string $year = null): array
    {
        return [];
    }

    public function fetchFeed(): array
    {
        return [];
    }

    public function fetchByType($type)
    {
        return [];
    }

    public function fetchByDate(DateTime $date, $language = 'is'): array
    {
        return [];
    }

    public function fetchAfter(DateTime $date, $language = 'is'): array
    {
        return [];
    }

    public function fetchAffected(): array
    {
        return [];
    }

    public function fetchYears(): array
    {
        return [];
    }

    public function fetchAll(): array
    {
        return [];
    }

    public function save(array $data): int
    {
        return 0;
    }

    public function attachAuthors(string $id, array $authors)
    {
    }

    public function attachImages(string $entryId, array $images, int $type = 1)
    {
    }

    public function delete(string $id): int
    {
        return 0;
    }
}
