<?php
namespace App\Service;

abstract class AbstracManifesto extends Manifesto
{
    public function __construct()
    {
    }
    public function get(string $id)
    {
    }

    public function fetch()
    {
    }

    public function getByType($type, $lang = 'is')
    {
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
