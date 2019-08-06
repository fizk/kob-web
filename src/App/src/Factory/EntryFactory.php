<?php
namespace App\Factory;

use PDO;
use App\Service;
use Psr\Container\ContainerInterface;

class EntryFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Service\Entry($container->get(PDO::class));
    }
}
