<?php
namespace App\Factory;


use App\Auth\SimpleAuthAdapter;
use App\Service;
use Psr\Container\ContainerInterface;

class SimpleAuthAdapterFactory
{
    public function __invoke(ContainerInterface $container) {
        return new SimpleAuthAdapter(
            $container->get(Service\User::class)
        );
    }
}
