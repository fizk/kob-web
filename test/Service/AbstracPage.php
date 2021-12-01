<?php
namespace App\Service;

use App\Model\Page;

abstract class AbstracPage extends PageService
{
    public function __construct()
    {
    }

    public function get(string $id): ?Page
    {
        return null;
    }

    public function fetch(): array
    {
        return [];
    }

    public function getByType($type, $lang = 'is'): ?Page
    {
        return null;
    }

    public function attachImages(string $manifestoId, array $images): array
    {
        return [];
    }

    public function save(Page $data): int
    {
        return 0;
    }
}
