<?php
namespace App\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Date extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('date', [$this, 'date']),
        ];
    }

    public function date($date)
    {
        return (new \DateTime($date))->format('Y.m.d');
    }
}
