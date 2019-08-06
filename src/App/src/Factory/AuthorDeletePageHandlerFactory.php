<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class AuthorDeletePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\AuthorDeletePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Author::class)
        );
    }
}
