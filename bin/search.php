<?php

declare(strict_types=1);

chdir(__DIR__ . '/../');

require 'vendor/autoload.php';
$config = include 'config/config.php';

$esHost = getenv('ES_HOST') ?: 'search';
$esProto = getenv('ES_PROTO') ?: 'http';
$esPort = getenv('ES_PORT') ?: 9201;
$esUser = getenv('ES_USER') ?: 'elastic';
$esPass = getenv('ES_PASSWORD') ?: 'changeme';

$hosts = [
    "{$esProto}://{$esUser}:{$esPass}@{$esHost}:{$esPort}",
];
$client = \Elasticsearch\ClientBuilder::create()
    ->setHosts($hosts)
    ->build();


$pdo = new PDO(
    $config['pdo']['dsn'],
    $config['pdo']['user'],
    $config['pdo']['password'],
    [
        PDO::MYSQL_ATTR_INIT_COMMAND =>
            "SET NAMES 'utf8', ".
            "sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]
);

$entryModel = new \App\Service\Entry($pdo);
$search = new \App\Service\Search($client);

foreach ($entryModel->fetchAll() as $item) {
    $entry = $entryModel->get((string) $item->id);

    if (($search->save($entry)) !== false ) {
        echo $item->id . " Success\n";
    } else {
        echo $item->id . " ERROR\n";
    }
}


