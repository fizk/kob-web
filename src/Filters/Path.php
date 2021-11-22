<?php
namespace App\Filters;

use App\Router\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Path extends AbstractExtension
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function path(string $name, array $params = []): string
    {
        return $this->router->generateUri($name, $params);
    }

    public function selectPath(array $name, bool $condition, array $params = []): string
    {

        return $this->router->generateUri($condition ? $name[0] : $name[1], $params);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'path']),
            new TwigFunction('selectPath', [$this, 'selectPath']),
        ];
    }
}
