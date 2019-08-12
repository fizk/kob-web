<?php

declare(strict_types=1);

chdir(__DIR__ . '/../');

require 'vendor/autoload.php';

$container = require 'config/container.php';

$client = $container->get(\Elasticsearch\Client::class);
$pdo = $container->get(\PDO::class);

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


