<?php
namespace App\Service;

use Psr\Http\Message\UploadedFileInterface;

abstract class AbstracAsset extends Asset
{
    private $resource;
    public function __construct()
    {
        $this->resource = fopen('php://memory', 'r+');
    }

    public function get(string $size, string $name)
    {
        return $this->resource;
    }

    public function save(UploadedFileInterface $value): array
    {
        return [];
    }

    public function __destruct()
    {
        fclose($this->resource);
    }
}
