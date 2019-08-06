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

class ManifestoSavePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\ManifestoSavePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Manifesto::class)
        );
    }
}
