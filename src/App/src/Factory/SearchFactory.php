<?php
namespace App\Factory;

use App\Service;
use Elasticsearch\Client;
use Psr\Container\ContainerInterface;

class SearchFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Service\Search($container->get(Client::class));
    }
}
