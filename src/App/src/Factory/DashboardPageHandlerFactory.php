<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class DashboardPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\DashboardPageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Entry::class),
            $container->get(Service\Author::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
