<?php
namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Service;
use PDO;

class UserFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Service\User($container->get(PDO::class));
    }
}
