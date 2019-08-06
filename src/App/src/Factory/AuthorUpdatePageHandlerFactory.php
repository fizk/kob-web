<?php
namespace App\Factory;

use App\Service;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuthorUpdatePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\AuthorUpdatePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Author::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
