<?php
namespace App\Filters;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Slug extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('slug', [$this, 'slug']),
        ];
    }

    public function slug($title, $id = 0)
    {
        // replace non letter or digits by -
        $title = preg_replace('~[^\pL\d]+~u', '-', $title);

        // transliterate
        $title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);

        // remove unwanted characters
        $title = preg_replace('~[^-\w]+~', '', $title);

        // trim
        $title = trim($title, '-');

        // remove duplicate -
        $title = preg_replace('~-+~', '-', $title);

        // lowercase
        $title = strtolower($title);

        if (empty($title)) {
            return 'n-a';
        }

        return "{$title}-{$id}";
    }
}
