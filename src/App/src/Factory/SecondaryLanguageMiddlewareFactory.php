<?php
namespace App\Factory;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Middleware;

class SecondaryLanguageMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Middleware\SecondaryLanguageMiddleware(
            $container->get(TemplateRendererInterface::class)
        );
    }
}
