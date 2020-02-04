<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class InventoryPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\InventoryUpdatePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Inventory::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
