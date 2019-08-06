<?php
namespace App\Factory;

use App\Filters;

class DateFactory
{
    public function __invoke()
    {
        return new Filters\Date();
    }
}
