<?php
/**
 * Created by PhpStorm.
 * User: einar.adalsteinsson
 * Date: 7/8/19
 * Time: 12:59 AM
 */

namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class EntrySavePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\EntrySavePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Entry::class),
            $container->get(Service\Author::class),
            $container->get(Service\Image::class),
            $container->get(Service\Search::class)
        );
    }
}
