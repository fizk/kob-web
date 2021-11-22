<?php
namespace App\Service;

use App\Model;

abstract class AbstracPage extends Page
{
    public function __construct()
    {
    }

    public function get(string $id): ?Model\Page
    {
        return null;
    }

    public function fetch(): array
    {
        return [];
    }

    public function getByType($type, $lang = 'is'): ?Model\Page
    {
        return null;
    }

    public function attachImages(string $manifestoId, array $images): array
    {
        return [];
    }

    public function save(array $data): int
    {
        return 0;
    }
}
