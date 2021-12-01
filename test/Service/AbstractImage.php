<?php
namespace App\Service;

use DateTime;
use App\Model\Image;

abstract class AbstractImage extends ImageService
{
    public function __construct()
    {
    }
    public function get(string $id): ?Image
    {
        return (new Image())
            ->setId(1)
            ->setName('name');
    }


    public function save(Image $data): int
    {
        return 0;
    }

    public function updateDescription(string $id, string $description, DateTime $affected)
    {
        return 1;
    }
}
