<?php
namespace App\Factory;

use App\Service;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ProjectsPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\ProjectsPageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Entry::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
