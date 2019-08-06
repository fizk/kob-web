<?php
namespace App\Factory;

use App\Middleware;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class PrimaryLanguageMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Middleware\PrimaryLanguageMiddleware(
            $container->get(TemplateRendererInterface::class)
        );
    }
}
