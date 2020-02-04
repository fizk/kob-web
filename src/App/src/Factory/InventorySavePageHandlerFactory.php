<?php
/**
 * Created by PhpStorm.
 * User: einar.adalsteinsson
 * Date: 7/8/19
 * Time: 1:14 AM
 */

namespace App\Factory;

use App\Service;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class InventorySavePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\InventorySavePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Inventory::class)
        );
    }
}
