<?php

declare(strict_types=1);

namespace App\Factory;

use App\Auth\SimpleAuthAdapter;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Router\RouterInterface;
use App\Handler;

class LogoutSubmitPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $router      = $container->get(RouterInterface::class);
        $authService = $container->get(AuthenticationService::class);
        $authAdapter = $container->get(SimpleAuthAdapter::class);

        return new Handler\LogoutSubmitPageHandler($router, $authService, $authAdapter);
    }
}
