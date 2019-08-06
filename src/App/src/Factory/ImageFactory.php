<?php
namespace App\Factory;

use PDO;
use App\Service;
use Psr\Container\ContainerInterface;

class ImageFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Service\Image($container->get(PDO::class));
    }
}
