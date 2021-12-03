<?php
namespace App\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Translate extends AbstractExtension
{
    public function translate(array $name, bool $condition): string
    {
        return $condition
            ? $name[0]
            : $name[1];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('_', [$this, 'translate']),
        ];
    }
}
