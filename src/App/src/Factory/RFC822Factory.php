<?php
namespace App\Factory;

use App\Filters;

class RFC822Factory
{
    public function __invoke()
    {
        return new Filters\RFC822();
    }
}
