<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class EntryPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\EntryPageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Entry::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
