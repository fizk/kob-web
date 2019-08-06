<?php
namespace App\Factory;

use PDO;
use App\Service;
use Psr\Container\ContainerInterface;

class AuthorFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Service\Author($container->get(PDO::class));
    }
}
