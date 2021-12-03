<?php

declare(strict_types=1);

chdir(__DIR__ . '/../');
require 'vendor/autoload.php';

use Laminas\ServiceManager\ServiceManager;

$serviceManager = new ServiceManager(require './config/service.php');

$client = $serviceManager->get(\Elasticsearch\Client::class);
$pdo = $serviceManager->get(\PDO::class);

$entryModel = new \App\Service\EntryService($pdo);
$search = new \App\Service\SearchService($client);

foreach ($entryModel->fetchAll() as $item) {
    $entry = $entryModel->get((string) $item->id);

    if (($search->save($entry)) !== false ) {
        echo $item->id . " Success\n";
    } else {
        echo $item->id . " ERROR\n";
    }
}
