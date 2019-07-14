<?php
namespace App\Factory;

use App\Auth\SimpleAuthAdapter;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;

class AuthenticationServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AuthenticationService(
            null,
            $container->get(SimpleAuthAdapter::class)
        );
    }
}
