<?php
namespace App\Factory;


use App\Auth\ParesDownAdapter;

class ParesDownAdapterFactory
{
    public function __invoke()
    {
        return new \Aptoma\Twig\Extension\MarkdownExtension(new ParesDownAdapter());
    }
}
