<?php
namespace App\Factory;

use App\Middleware;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class MenuMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Middleware\MenuMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get(Service\Entry::class)
        );
    }
}
