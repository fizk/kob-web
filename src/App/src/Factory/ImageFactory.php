<?php
namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Service;
use PDO;

class ImageFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Service\Image($container->get(PDO::class));
    }
}
