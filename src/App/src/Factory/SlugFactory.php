<?php
namespace App\Factory;

use App\Filters;

class SlugFactory
{
    public function __invoke()
    {
        return new Filters\Slug();
    }
}
