<?php
namespace App\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RFC822 extends AbstractExtension
{


    public function getFilters()
    {
        return [
            new TwigFilter('RFC822', [$this, 'RFC822']),
        ];
    }

    public function RFC822($date)
    {
        try {
            return (new \DateTime($date))->format('r');
        } catch (\Exception $e) {
            return '';
        }

    }
}
