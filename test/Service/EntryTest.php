<?php

namespace App\Service;

use DateTime;
use PHPUnit\Framework\TestCase;

use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;

use PDO;

class EntryTest extends TestCase
{
    use MySQLTestCaseTrait;
    protected ?PDO $pdo = null;
    static protected $connection;

    public function testTrue()
    {
        // $service = new Entry($this->pdo);
        // $all = $service->fetchPromoted(new DateTime('2001-06-01'));

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        try {
            $this->getDatabase()->exec('SET foreign_key_checks=0');
            $this->getDatabase()->exec('truncate table Entry');
        } finally {
            $this->getDatabase()->exec('SET foreign_key_checks=1');
        }
    }

    protected function getDatabase(): PDO
    {
        $dbName = getenv('DB_NAME');
        $dbHost = getenv('DB_HOST');
        $dbPort = getenv('DB_PORT');

        self::$connection = $this->pdo = self::$connection ?: new PDO(
            "mysql:host={$dbHost};port={$dbPort};dbname={$dbName}",
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            [
                PDO::MYSQL_ATTR_INIT_COMMAND =>
                "SET NAMES 'utf8', " .
                "sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ]
        );
        return self::$connection;
    }

    protected function getDatasetForSetup(): Dataset
    {
        return new DatasetArray([
            'Entry' => [
                [
                    'id' => 1,
                    'title' => 'show #1',
                    'from' => '2001-06-01',
                    'to' => '2001-07-01',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                    'type' => 'show',
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                ],
            ],
        ]);
    }
}
