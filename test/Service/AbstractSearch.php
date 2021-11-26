<?php
namespace App\Service;

abstract class AbstractSearch extends SearchService
{
    public function __construct()
    {
    }
    public function search(string $query, $language = 'is')
    {

    }

    public function save($item): bool
    {
        return false;
    }
}
