<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class EntryCreatePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\EntryCreatePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Entry::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
