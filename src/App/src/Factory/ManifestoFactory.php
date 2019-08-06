<?php
namespace App\Factory;

use PDO;
use App\Service;
use Psr\Container\ContainerInterface;

class ManifestoFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Service\Manifesto($container->get(PDO::class));
    }
}
