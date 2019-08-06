<?php
namespace App\Factory;

use App\Auth\SimpleAuthAdapter;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Router\RouterInterface;

class LoginSubmitPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\LoginSubmitPageHandler(
            $container->get(RouterInterface::class),
            $container->get(AuthenticationService::class),
            $container->get(SimpleAuthAdapter::class)
        );
    }
}
