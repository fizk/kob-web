<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Authentication\AuthenticationService;
use App\Middleware;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $authentication = $container->get(AuthenticationService::class);

        return new Middleware\AuthenticationMiddleware($authentication);
    }
}
