<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;

use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;

use PDO;

class AuthorTest extends TestCase
{
    use MySQLTestCaseTrait;
    protected ?PDO $pdo = null;
    static protected $connection;

    public function testGet()
    {
        $service = new Author($this->pdo);
        $expected = (object)[
            'id' => '1',
            'name' => 'author 1',
            'created' => '2001-01-01 00:00:00',
            'affected' => '2001-01-01 00:00:00',
        ];
        $actual = $service->get('1');

        $this->assertEquals($expected, $actual);
    }

    public function testFetch()
    {
        $service = new Author($this->pdo);
        $expected = (object)[
            'id' => '1',
            'name' => 'author 1',
            'created' => '2001-01-01 00:00:00',
            'affected' => '2001-01-01 00:00:00',
            'entries' => [
                (object)[
                    'id' => '1',
                    'title' => 'show #1',
                    'from' => '2001-06-01',
                    'to' => '2001-07-01',
                    'created' => '2001-01-01 00:00:00',
                    'affected' => '2001-01-01 00:00:00',
                    'type' => Entry::PROJECT,
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                    'gallery' => [],
                    'poster' => (object)[
                        'id' => '1',
                        'name' => 'name1',
                        'description' => null,
                        'size' => '0',
                        'width' => '0',
                        'height' => '0',
                        'created' => '2001-01-01 00:00:00',
                        'affected' => '2001-01-01 00:00:00',
                        'type' => '1',
                    ],
                    'authors' => [
                        (object)[
                            'id' => '1',
                            'name' => 'author 1',
                            'created' => '2001-01-01 00:00:00',
                            'affected' => '2001-01-01 00:00:00',
                        ],
                        (object)[
                            'id' => '2',
                            'name' => 'author 2',
                            'created' => '2001-01-01 00:00:00',
                            'affected' => '2001-01-01 00:00:00',
                        ]
                    ]
                ]
            ]
        ];
        $actual = $service->fetch('1');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchError()
    {
        $service = new Author($this->pdo);
        $actual = $service->fetch('1234567');

        $this->assertNull($actual);
    }

    protected function tearDown(): void
    {
        try {
            $this->getDatabase()->exec('SET foreign_key_checks=0');
            $this->getDatabase()->exec('truncate table Entry');
            $this->getDatabase()->exec('truncate table Author');
            $this->getDatabase()->exec('truncate table Entry_has_Author');
            $this->getDatabase()->exec('truncate table Image');
            $this->getDatabase()->exec('truncate table Entry_has_Image');
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
                    'type' => Entry::PROJECT,
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                ],
                [
                    'id' => 2,
                    'title' => 'show #2',
                    'from' => '2001-06-01',
                    'to' => '2001-07-01',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                    'type' => Entry::SHOW,
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                ],
                // ------------------
                [
                    'id' => 3,
                    'title' => 'show #3',
                    'from' => '2010-01-01',
                    'to' => '2010-02-01',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                    'type' => Entry::PROJECT,
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                ],
                [
                    'id' => 4,
                    'title' => 'show #4',
                    'from' => '2010-01-15',
                    'to' => '2010-02-01',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                    'type' => Entry::SHOW,
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                ],
                [
                    'id' => 5,
                    'title' => 'show #5',
                    'from' => '2010-01-02',
                    'to' => '2010-01-31',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                    'type' => Entry::SHOW,
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                ],
                // ------------------
                [
                    'id' => 6,
                    'title' => 'show #5',
                    'from' => '2020-01-01',
                    'to' => '2020-01-01',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                    'type' => Entry::PROJECT,
                    'body_is' => 'is',
                    'body_en' => 'en',
                    'orientation' => '',
                ],
            ],
            'Author' => [
                [
                    'id' => '1',
                    'name' => 'author 1',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                ],
                [
                    'id' => '2',
                    'name' => 'author 2',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                ],
                [
                    'id' => '3',
                    'name' => 'author 3',
                    'created' => '2001-01-01',
                    'affected' => '2001-01-01',
                ],
            ],
            'Entry_has_Author' => [
                [
                    'entry_id' => '1',
                    'author_id' => '1',
                    'order' => '1',
                ],
                [
                    'entry_id' => '1',
                    'author_id' => '2',
                    'order' => '2',
                ],
                [
                    'entry_id' => '4',
                    'author_id' => '2',
                    'order' => '1',
                ],
                [
                    'entry_id' => '6',
                    'author_id' => '2',
                    'order' => '1',
                ],
            ],
            'Image' => [
                [
                    'id' => '1',
                    'name' => 'name1',
                    'description' => null,
                    'size' => 0,
                    'width' => 0,
                    'height' => 0,
                    'created' => '2001-01-01 00:00:00',
                    'affected' => '2001-01-01 00:00:00',
                ],
                [
                    'id' => '2',
                    'name' => 'name2',
                    'description' => null,
                    'size' => 0,
                    'width' => 0,
                    'height' => 0,
                    'created' => '2001-01-01 00:00:00',
                    'affected' => '2001-01-01 00:00:00',
                ],
            ],
            'Entry_has_Image' => [
                [
                    'image_id' => '1',
                    'entry_id' => '1',
                    'order' => '1',
                    'type' => '1',
                ],
                [
                    'image_id' => '2',
                    'entry_id' => '1',
                    'order' => '1',
                    'type' => '2',
                ],
                [
                    'image_id' => '1',
                    'entry_id' => '4',
                    'order' => '1',
                    'type' => '1',
                ],
                [
                    'image_id' => '2',
                    'entry_id' => '4',
                    'order' => '1',
                    'type' => '2',
                ],
                [
                    'image_id' => '1',
                    'entry_id' => '6',
                    'order' => '1',
                    'type' => '1',
                ],
                [
                    'image_id' => '2',
                    'entry_id' => '6',
                    'order' => '1',
                    'type' => '2',
                ],
            ],
        ]);
    }
}
