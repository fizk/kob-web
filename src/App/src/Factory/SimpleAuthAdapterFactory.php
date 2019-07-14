<?php
namespace App\Factory;

use App\Auth\SimpleAuthAdapter;
use App\Service;
use Interop\Container\ContainerInterface;

class SimpleAuthAdapterFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $user = $container->get(Service\User::class);
        return new SimpleAuthAdapter($user);
    }
}
