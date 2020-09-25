<?php
namespace App\Service;

use DateTime;

abstract class AbstractImage extends Image
{
    public function __construct()
    {
    }
    public function get(string $id): \stdClass
    {
        return new \stdClass;
    }


    public function save(array $data): int
    {
        return 0;
    }

    public function updateDescription(string $id, string $description, DateTime $affected)
    {
        return 1;
    }
}
