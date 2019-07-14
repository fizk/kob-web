<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Middleware;
use App\Service;

class MenuMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $entry = $container->get(Service\Entry::class);

        return new Middleware\MenuMiddleware($template, $entry);
    }
}
