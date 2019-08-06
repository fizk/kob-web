<?php
namespace App\Factory;

use App\Middleware;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AdminMenuMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Middleware\AdminMenuMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get(Service\Manifesto::class)
        );
    }
}
