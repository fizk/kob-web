<?php
namespace App\Filters;

use App\Router\RouteCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Path extends AbstractExtension
{
    private RouteCollection $router;

    public function __construct(RouteCollection $router)
    {
        $this->router = $router;
    }

    public function path(string $name, array $params = []): string
    {
        return $this->router->generateUri($name, $params);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'path']),
        ];
    }
}
