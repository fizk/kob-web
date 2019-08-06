<?php
namespace App\Factory;

use App\Service;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class EntryDeletePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\EntryDeletePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Entry::class)
        );
    }
}
