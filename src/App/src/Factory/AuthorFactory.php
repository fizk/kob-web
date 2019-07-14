<?php
namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Service;
use PDO;

class AuthorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Service\Author($container->get(PDO::class));
    }
}
