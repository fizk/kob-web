<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;
use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;
use App\Model;
use DateTime;
use PDO;

class AuthorTest extends TestCase
{
    use MySQLTestCaseTrait;
    protected ?PDO $pdo = null;
    static protected $connection;

    public function testGet()
    {
        $service = new Author($this->pdo);
        $expected = (new Model\Author())
            ->setId(1)
            ->setName('author 1')
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'));

        $actual = $service->get('1');

        $this->assertEquals($expected, $actual);
    }

    public function testFetch()
    {
        $service = new Author($this->pdo);
        $expected = (new Model\Author())
            ->setId(1)
            ->setName('author 1')
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setEntries([
                (new Model\Entry())
                ->setId(1)
                ->setTitle('show #1')
                ->setFrom(new DateTime('2001-06-01'))
                ->setTo(new DateTime('2001-07-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::PROJECT)
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation('')
                ->setAuthors([
                    (new Model\Author())
                        ->setId(1)
                        ->setName('author 1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                    (new Model\Author())
                        ->setId(2)
                        ->setName('author 2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                    ])
                    ->setPoster(
                        (new Model\Image())
                            ->setId(1)
                            ->setName('name1')
                            ->setCreated(new DateTime('2001-01-01 00:00:00'))
                            ->setAffected(new DateTime('2001-01-01 00:00:00'))
                    )

            ])
            ;

        $actual = $service->fetch('1');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchError()
    {
        $service = new Author($this->pdo);
        $actual = $service->fetch('1234567');

        $this->assertNull($actual);
    }

    public function testFetchList()
    {
        $service = new Author($this->pdo);
        $expected = [
            (new Model\Author())
                ->setId(1)
                ->setName('author 1')
                ->setCreated(new DateTime('2001-01-01'))
                ->setAffected(new DateTime('2001-01-01'))
                ->setEntries([
                    (new Model\Entry())
                    ->setId(1)
                    ->setTitle('show #1')
                    ->setFrom(new DateTime('2001-06-01'))
                    ->setTo(new DateTime('2001-07-01'))
                    ->setCreated(new DateTime('2001-01-01 00:00:00'))
                    ->setAffected(new DateTime('2001-01-01 00:00:00'))
                    ->setType(Entry::PROJECT)
                    ->setBodyIs('is')
                    ->setBodyEn('en')
                    ->setOrientation('')
                    ->setAuthors([
                        (new Model\Author())
                        ->setId(1)
                        ->setName('author 1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                        (new Model\Author())
                        ->setId(2)
                        ->setName('author 2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                    ])
                    ->setPoster(
                        (new Model\Image())
                        ->setId(1)
                        ->setName('name1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                    ),
                ]),
            (new Model\Author())
                ->setId(2)
                ->setName('author 2')
                ->setCreated(new DateTime('2001-01-01'))
                ->setAffected(new DateTime('2001-01-01'))
                ->setEntries([
                    (new Model\Entry())
                        ->setId(1)
                        ->setTitle('show #1')
                        ->setFrom(new DateTime('2001-06-01'))
                        ->setTo(new DateTime('2001-07-01'))
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ->setType(Entry::PROJECT)
                        ->setBodyIs('is')
                        ->setBodyEn('en')
                        ->setOrientation('')
                        ->setAuthors([
                            (new Model\Author())
                                ->setId(1)
                                ->setName('author 1')
                                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                                ->setAffected(new DateTime('2001-01-01 00:00:00')),
                            (new Model\Author())
                                ->setId(2)
                                ->setName('author 2')
                                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                                ->setAffected(new DateTime('2001-01-01 00:00:00')),
                        ])
                        ->setPoster(
                            (new Model\Image())
                                ->setId(1)
                                ->setName('name1')
                                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ),
                    (new Model\Entry())
                        ->setId(4)
                        ->setTitle('show #4')
                        ->setFrom(new DateTime('2010-01-15'))
                        ->setTo(new DateTime('2010-02-01'))
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ->setType(Entry::SHOW)
                        ->setBodyIs('is')
                        ->setBodyEn('en')
                        ->setOrientation('')
                        ->setPoster(
                            (new Model\Image())
                                ->setId(1)
                                ->setName('name1')
                                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        )
                        ->setAuthors([
                            (new Model\Author())
                                ->setId(2)
                                ->setName('author 2')
                                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ]),
                    (new Model\Entry())
                        ->setId(6)
                        ->setTitle('show #6')
                        ->setFrom(new DateTime('2020-01-01'))
                        ->setTo(new DateTime('2020-01-01'))
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ->setType(Entry::PROJECT)
                        ->setBodyIs('is')
                        ->setBodyEn('en')
                        ->setOrientation('')
                        ->setPoster(
                            (new Model\Image())
                                ->setId(1)
                                ->setName('name1')
                                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        )
                        ->setAuthors([
                            (new Model\Author())
                                ->setId(2)
                                ->setName('author 2')
                                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                        ]),
            ]),
            (new Model\Author())
                ->setId(3)
                ->setName('author 3')
                ->setCreated(new DateTime('2001-01-01'))
                ->setAffected(new DateTime('2001-01-01')),
        ];
        $actual = $service->fetchList();

        $this->assertEquals($expected, $actual);
    }

    public function testFetchAffected()
    {
        $service = new Author($this->pdo);
        $actual = $service->fetchAffected();
        $this->assertCount(3, $actual);
    }

    public function testUpdate()
    {
        $service = new Author($this->pdo);

        $service->save([
            'id' => '1',
            'name' => 'author one',
            'created' => '2001-01-01',
            'affected' => '2001-01-01',
        ]);

        $expected = [
            (object)[
                'id' => '1',
                'name' => 'author one',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
            (object)[
                'id' => '2',
                'name' => 'author 2',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
            (object)[
                'id' => '3',
                'name' => 'author 3',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Author');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testSave()
    {
        $service = new Author($this->pdo);

        $id = $service->save([
            'name' => 'author new',
            'created' => '2001-01-01',
            'affected' => '2001-01-01',
        ]);

        $expected = [
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
            ],
            (object)[
                'id' => '3',
                'name' => 'author 3',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
            (object)[
                'id' => $id,
                'name' => 'author new',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Author');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testDelete()
    {
        $service = new Author($this->pdo);

        $count = $service->delete('1');

        $expected = [
            (object)[
                'id' => '2',
                'name' => 'author 2',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
            (object)[
                'id' => '3',
                'name' => 'author 3',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Author');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
        $this->assertEquals(1, $count);
    }

    public function testDeleteNotFound()
    {
        $service = new Author($this->pdo);

        $count = $service->delete('12345678');

        $expected = [
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
            ],
            (object)[
                'id' => '3',
                'name' => 'author 3',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Author');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
        $this->assertEquals(0, $count);
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
                    'title' => 'show #6',
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
