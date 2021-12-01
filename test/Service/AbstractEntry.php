<?php

namespace App\Service;


use DateTime;
use App\Model\{Entry, Entries};

abstract class AbstractEntry extends EntryService
{
    public function __construct()
    {
    }

    public function get(string $id): ?Entry
    {
        return (new Entry)
            ->setId(1)
            ->setTitle('title1')
            ->setType(Entry::NEWS)
            ->setFrom(new DateTime())
            ->setOrientation('')
            ->setTo(new DateTime());
    }

    public function fetch(string $id, string $lang = 'is'): ?Entries
    {
        return (new Entries());
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

    public function fetchByType(string $type): array
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

    public function save(Entry $entry): int
    {
        $i = 0;
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
