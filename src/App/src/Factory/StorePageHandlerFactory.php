<?php
/**
 * Created by PhpStorm.
 * User: einar.adalsteinsson
 * Date: 7/8/19
 * Time: 1:06 AM
 */

namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class StorePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\StorePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Manifesto::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
