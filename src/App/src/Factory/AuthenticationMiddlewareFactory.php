<?php
namespace App\Factory;

use App\Middleware;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Middleware\AuthenticationMiddleware(
            $container->get(AuthenticationService::class)
        );
    }
}
