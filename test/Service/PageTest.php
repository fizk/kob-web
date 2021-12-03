<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;

use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;

use App\Model\{Image, Page};
use DateTime;
use PDO;

class PageTest extends TestCase
{
    use MySQLTestCaseTrait;
    protected ?PDO $pdo = null;
    static protected $connection;

    public function testGet()
    {
        $service = new PageService($this->pdo);
        $expected = (new Page())
            ->setId(1)
            ->setType('type1')
            ->setBodyEn('en')
            ->setBodyIs('is')
            ->setGallery([
                (new Image())
                    ->setId(1)
                    ->setName('name1')
                    ->setCreated(new DateTime('2001-01-01 00:00:00'))
                    ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ])
            ;

        $actual = $service->get('1');

        $this->assertEquals($expected, $actual);
    }

    public function testGetByType()
    {
        $service = new PageService($this->pdo);
        $expected = (new Page())
            ->setId(1)
            ->setType('type1')
            ->setBody('is')
            ->setBodyEn('en')
            ->setBodyIs('is')
            ->setGallery([
                (new Image())
                    ->setId(1)
                    ->setName('name1')
                    ->setCreated(new DateTime('2001-01-01 00:00:00'))
                    ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ])
            ;

        $actual = $service->getByType('type1');

        $this->assertEquals($expected, $actual);
    }

    public function testGetByTypeNotFound()
    {
        $service = new PageService($this->pdo);
        $expected = null;

        $actual = $service->getByType('this is not an entry');

        $this->assertEquals($expected, $actual);
    }

    public function testFetch()
    {
        $service = new PageService($this->pdo);
        $actual = $service->fetch();

        $this->assertCount(3, $actual);
    }

    public function testUpdate()
    {
        $service = new PageService($this->pdo);

        $service->save(
            (new Page())
                ->setId(1)
                ->setType('type1')
                ->setBodyIs('updated is')
                ->setBodyEn('updated en')
         );

        $expected = [
            (object)[
                'id' => '1',
                'type' => 'type1',
                'body_is' => 'updated is',
                'body_en' => 'updated en',
            ],
            (object)[
                'id' => '2',
                'type' => 'type2',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
            (object)[
                'id' => '3',
                'type' => 'type3',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Manifesto');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testSave()
    {
        $service = new PageService($this->pdo);

        $id = $service->save(
            (new Page())
                ->setType('new')
                ->setBodyIs('is')
                ->setBodyEn('en')
         );

        $expected = [
            (object)[
                'id' => '1',
                'type' => 'type1',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
            (object)[
                'id' => '2',
                'type' => 'type2',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
            (object)[
                'id' => '3',
                'type' => 'type3',
                'body_is' => 'is',
                'body_en' => 'en',
            ],
            (object)[
                'id' => $id,
                'type' => 'new',
                'body_is' => 'is',
                'body_en' => 'en',
            ]
        ];

        $statement = $this->getDatabase()->prepare('select * from Manifesto');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    protected function tearDown(): void
    {
        try {
            $this->getDatabase()->exec('SET foreign_key_checks=0');
            $this->getDatabase()->exec('truncate table Author');
            $this->getDatabase()->exec('truncate table Image');
            $this->getDatabase()->exec('truncate table Manifesto_has_Image');
            $this->getDatabase()->exec('truncate table Manifesto');
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
            'Manifesto' => [
                [
                    'id' => '1',
                    'type' => 'type1',
                    'body_is' => 'is',
                    'body_en' => 'en',
                ],
                [
                    'id' => '2',
                    'type' => 'type2',
                    'body_is' => 'is',
                    'body_en' => 'en',
                ],
                [
                    'id' => '3',
                    'type' => 'type3',
                    'body_is' => 'is',
                    'body_en' => 'en',
                ],
            ],
            'Manifesto_has_Image' => [
                [
                    'image_id' => '1',
                    'entry_id' => '1',
                    'order' => '1',
                    'type' => '1',
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
