<?php
namespace App\Factory;

use App\Service;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\HomePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Entry::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
