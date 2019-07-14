<?php
namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Service;
use PDO;

class ManifestoFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Service\Manifesto($container->get(PDO::class));
    }
}
