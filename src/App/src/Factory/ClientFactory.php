<?php
namespace App\Factory;

use Elasticsearch\ClientBuilder;
use Psr\Container\ContainerInterface;

class ClientFactory
{
    public function __invoke(ContainerInterface $container) {
        $esHost = getenv('ES_HOST') ?: 'search';
        $esProto = getenv('ES_PROTO') ?: 'http';
        $esPort = getenv('ES_PORT') ?: 9200;
        $esUser = getenv('ES_USER') ?: 'elastic';
        $esPass = getenv('ES_PASSWORD') ?: 'changeme';

        $hosts = [
            "{$esProto}://{$esUser}:{$esPass}@{$esHost}:{$esPort}",
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();

        return $client;
    }
}
