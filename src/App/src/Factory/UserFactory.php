<?php
namespace App\Factory;

use PDO;
use App\Service;
use Psr\Container\ContainerInterface;

class UserFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Service\User($container->get(PDO::class));
    }
}
