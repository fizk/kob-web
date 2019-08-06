<?php
namespace App\Factory;

use App\Service;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class AuthorSavePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\AuthorSavePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Author::class)
        );
    }
}
