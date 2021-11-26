<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;
use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;
use App\Model\{Store, Author, Image};
use DateTime;
use PDO;

class StoreTest extends TestCase
{
    use MySQLTestCaseTrait;
    protected ?PDO $pdo = null;
    static protected $connection;

    public function testGet()
    {
        $service = new StoreService($this->pdo);
        $expected = (new Store())
            ->setId(1)
            ->setTitle('title 1')
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setBodyIs('is')
            ->setBodyEn('en')
        ;

        $actual = $service->get('1');

        $this->assertEquals($expected, $actual);
    }

    public function testFetch()
    {
        $service = new StoreService($this->pdo);
        $expected = (new Store())
            ->setId(2)
            ->setTitle('title 2')
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setBodyIs('is')
            ->setBodyEn('en')
            ->setAuthors([(new Author)
                ->setId(1)
                ->setName('author 1')
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setOrder(1)
            ])
            ->setGallery([(new Image())
                ->setId(1)
                ->setName('name1')
                ->setSize(0)
                ->setWidth(0)
                ->setHeight(0)
                ->setOrder(1)
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ])
        ;

        $actual = $service->fetch('2');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchAllIS()
    {
        $service = new StoreService($this->pdo);
        $expected = [
            (new Store())
                ->setId(1)
                ->setTitle('title 1')
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setBody('is'),
            (new Store())
                ->setId(2)
                ->setTitle('title 2')
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setBody('is')
                ->setAuthors([
                    (new Author)
                        ->setId(1)
                        ->setName('author 1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ->setOrder(1)
                ])
                ->setGallery([
                    (new Image())
                        ->setId(1)
                        ->setName('name1')
                        ->setSize(0)
                        ->setWidth(0)
                        ->setHeight(0)
                        ->setOrder(1)
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
        ];
        $actual = $service->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testFetchAll()
    {
        $service = new StoreService($this->pdo);
        $expected = [
            (new Store())
                ->setId(1)
                ->setTitle('title 1')
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setBody('en'),
            (new Store())
                ->setId(2)
                ->setTitle('title 2')
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setBody('en')
                ->setAuthors([
                    (new Author)
                        ->setId(1)
                        ->setName('author 1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ->setOrder(1)
                ])
                ->setGallery([
                    (new Image())
                        ->setId(1)
                        ->setName('name1')
                        ->setSize(0)
                        ->setWidth(0)
                        ->setHeight(0)
                        ->setOrder(1)
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
        ];
        $actual = $service->fetchAll('en');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchNotFound()
    {
        $service = new StoreService($this->pdo);
        $expected = null;

        $actual = $service->fetch('2745582');

        $this->assertEquals($expected, $actual);
    }

    public function testGetNotFound()
    {
        $service = new StoreService($this->pdo);
        $expected = null;

        $actual = $service->get('123456789');

        $this->assertEquals($expected, $actual);
    }

    public function testUpdate()
    {
        $service = new StoreService($this->pdo);

        $service->save([
            'id' => '1',
            'title' => 'title one',
            'created' => '2001-01-01 00:00:00',
            'affected' => '2001-01-01 00:00:00',
            'body_is' => 'is',
            'body_en' => 'en',
        ]);

        $expected = [
            (object)[
                'id' => '1',
                'title' => 'title one',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
                ],
            (object)[
                'id' => '2',
                'title' => 'title 2',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Store');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testSave()
    {
        $service = new StoreService($this->pdo);

        $id = $service->save([
            'title' => 'title three',
            'created' => '2001-01-01 00:00:00',
            'affected' => '2001-01-01 00:00:00',
            'body_is' => 'is',
            'body_en' => 'en',
        ]);

        $expected = [
            (object)[
                'id' => '1',
                'title' => 'title 1',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
                ],
            (object)[
                'id' => '2',
                'title' => 'title 2',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
            (object)[
                'id' => $id,
                'title' => 'title three',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
            ]
        ];

        $statement = $this->getDatabase()->prepare('select * from Store');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testDeleteNotFound()
    {
        $service = new StoreService($this->pdo);

        $count = $service->delete('12345678');

        $expected = [
            (object)[
                'id' => '1',
                'title' => 'title 1',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
            (object)[
                'id' => '2',
                'title' => 'title 2',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Store');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
        $this->assertEquals(0, $count);
    }
    public function testDelete()
    {
        $service = new StoreService($this->pdo);

        $count = $service->delete('1');

        $expected = [
            (object)[
                'id' => '2',
                'title' => 'title 2',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Store');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
        $this->assertEquals(1, $count);
    }

    protected function tearDown(): void
    {
        try {
            $this->getDatabase()->exec('SET foreign_key_checks=0');
            $this->getDatabase()->exec('truncate table Image');
            $this->getDatabase()->exec('truncate table Store');
            $this->getDatabase()->exec('truncate table Author');
            $this->getDatabase()->exec('truncate table Store_has_Image');
            $this->getDatabase()->exec('truncate table Store_has_Author');
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
            'Store' => [
                [
                    'id' => '1',
                    'title' => 'title 1',
                    'created' => '2001-01-01 00:00:00',
                    'affected' => '2001-01-01 00:00:00',
                    'body_is' => 'is',
                    'body_en' => 'en',
                ],
                [
                    'id' => '2',
                    'title' => 'title 2',
                    'created' => '2001-01-01 00:00:00',
                    'affected' => '2001-01-01 00:00:00',
                    'body_is' => 'is',
                    'body_en' => 'en',
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
            'Store_has_Image' => [
                [
                    'image_id' => '1',
                    'store_id' => '2',
                    'order' => '1',
                ],
                [
                    'image_id' => '1',
                    'store_id' => '3',
                    'order' => '1',
                ]
            ],
            'Store_has_Author' => [
                [
                    'store_id' => '2',
                    'author_id' => '1',
                    'order' => '1',
                ]
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
        ]);
    }
}
