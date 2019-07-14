<?php
namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Service;
use PDO;

class EntryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Service\Entry($container->get(PDO::class));
    }
}
