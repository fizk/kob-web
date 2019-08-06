<?php
namespace App\Factory;

use App\Handler;
use Psr\Container\ContainerInterface;

class AssetPageHandlerFactory
{
    public function __invoke (ContainerInterface $container) {
        return new Handler\AssetPageHandler();
    }
}
