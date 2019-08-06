<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ManifestoPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\ManifestoPageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Manifesto::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}