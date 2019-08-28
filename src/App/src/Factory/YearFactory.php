<?php
namespace App\Factory;

use App\Filters;

class YearFactory
{
    public function __invoke()
    {
        return new Filters\Year();
    }
}
