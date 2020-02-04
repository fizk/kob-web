<?php
namespace App\Factory;

use PDO;
use App\Service;
use Psr\Container\ContainerInterface;

class InventoryFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Service\Inventory($container->get(PDO::class));
    }
}
