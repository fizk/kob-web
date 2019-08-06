<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuthorsPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\AuthorsPageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Author::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
