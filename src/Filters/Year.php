<?php
namespace App\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Year extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('year', [$this, 'year']),
        ];
    }

    public function year($date): string
    {
        try {
            return (new \DateTime($date))->format('Y');
        } catch (\Exception $e) {
            return '';
        }
    }
}
