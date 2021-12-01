<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;
use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;
use App\Model\{Entry, Entries, Author, Image};
use DateTime;
use PDO;

class EntryTest extends TestCase
{
    use MySQLTestCaseTrait;
    protected ?PDO $pdo = null;
    static protected $connection;

    public function testGet()
    {
        $service = new EntryService($this->pdo);
        $expected = (new Entry())
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
                (new Author())
                    ->setId(1)
                    ->setName('author 1')
                    ->setCreated(new DateTime('2001-01-01 00:00:00'))
                    ->setAffected(new DateTime('2001-01-01 00:00:00')),
                (new Author())
                    ->setId(2)
                    ->setName('author 2')
                    ->setCreated(new DateTime('2001-01-01 00:00:00'))
                    ->setAffected(new DateTime('2001-01-01 00:00:00')),
            ])
            ->setPosters([
                (new Image())
                    ->setId(1)
                    ->setName('name1')
                    ->setCreated(new DateTime('2001-01-01 00:00:00'))
                    ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ])
            ->setGallery([
                (new Image())
                    ->setId(2)
                    ->setName('name2')
                    ->setCreated(new DateTime('2001-01-01 00:00:00'))
                    ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ]);

        $actual = $service->get('1');

        $this->assertEquals($expected, $actual);
    }

    public function testFetch()
    {
        $service = new EntryService($this->pdo);
        $expected = (new Entries())
            ->setPrevious((new Entry())
                ->setId(3)
                ->setTitle('show #3')
                ->setFrom(new DateTime('2010-01-01'))
                ->setTo(new DateTime('2010-02-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::PROJECT)
                ->setBody('is')
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation(''))
            ->setCurrent((new Entry())
                ->setId(1)
                ->setTitle('show #1')
                ->setFrom(new DateTime('2001-06-01'))
                ->setTo(new DateTime('2001-07-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::PROJECT)
                ->setBody('is')
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation('')
                ->setAuthors([
                    (new Author())
                        ->setId(1)
                        ->setName('author 1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                    (new Author())
                        ->setId(2)
                        ->setName('author 2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                ])
                ->setPosters([
                    (new Image())
                        ->setId(1)
                        ->setName('name1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
                ->setGallery([
                    (new Image())
                        ->setId(2)
                        ->setName('name2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ]))
            ->setNext((new Entry())
                ->setId(2)
                ->setTitle('show #2')
                ->setFrom(new DateTime('2001-06-01'))
                ->setTo(new DateTime('2001-07-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::SHOW)
                ->setBody('is')
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation(''));

        $actual = $service->fetch('1', 'is');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchCurrent()
    {
        $service = new EntryService($this->pdo);
        $expected = [
            (new Entry())
                ->setId(3)
                ->setTitle('show #3')
                ->setFrom(new DateTime('2010-01-01'))
                ->setTo(new DateTime('2010-02-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::PROJECT)
                ->setBody('is')
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation(''),
            (new Entry())
                ->setId(5)
                ->setTitle('show #5')
                ->setFrom(new DateTime('2010-01-02'))
                ->setTo(new DateTime('2010-01-31'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::SHOW)
                ->setBody('is')
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation(''),
            (new Entry())
                ->setId(4)
                ->setTitle('show #4')
                ->setFrom(new DateTime('2010-01-15'))
                ->setTo(new DateTime('2010-02-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::SHOW)
                ->setBody('is')
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation('')
                ->setPosters([
                    (new Image())
                        ->setId(1)
                        ->setName('name1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
                ->setGallery([
                    (new Image())
                        ->setId(2)
                        ->setName('name2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
                ->setAuthors([
                    (new Author())
                        ->setId(2)
                        ->setName('author 2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
        ];

        $actual = $service->fetchCurrent(new DateTime('2010-01-15'), 'is');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchLatestByType()
    {
        $service = new EntryService($this->pdo);
        $expected = [
            (new Entry())
                ->setId(6)
                ->setTitle('show #6')
                ->setFrom(new DateTime('2020-01-01'))
                ->setTo(new DateTime('2020-01-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::PROJECT)
                ->setBody('is')
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation('')
                ->setPosters([
                    (new Image())
                        ->setId(1)
                        ->setName('name1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
                ->setGallery([
                    (new Image())
                        ->setId(2)
                        ->setName('name2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
                ->setAuthors([
                    (new Author())
                        ->setId(2)
                        ->setName('author 2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ]),
        ];

        $actual = $service->fetchLatestByType(Entry::PROJECT, 'is');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchList()
    {
        $service = new EntryService($this->pdo);
        $expected = ['6','4','5','3','1','2'];
        $list = $service->fetchList();
        $actual = array_map(function($item) {
            return $item->getId();
        }, $list);

        $this->assertEquals($expected, $actual);
    }

    public function testFetchListByYear()
    {
        $service = new EntryService($this->pdo);
        $expected = [
            (new Entry())
                ->setId(1)
                ->setTitle('show #1')
                ->setFrom(new DateTime('2001-06-01'))
                ->setTo(new DateTime('2001-07-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::PROJECT)
                ->setBody(null)
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation('')
                ->setAuthors([
                    (new Author())
                        ->setId(1)
                        ->setName('author 1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                    (new Author())
                        ->setId(2)
                        ->setName('author 2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00')),
                ])
                ->setPosters([
                    (new Image())
                        ->setId(1)
                        ->setName('name1')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ])
                ->setGallery([
                    (new Image())
                        ->setId(2)
                        ->setName('name2')
                        ->setCreated(new DateTime('2001-01-01 00:00:00'))
                        ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ]),
            (new Entry())
                ->setId(2)
                ->setTitle('show #2')
                ->setFrom(new DateTime('2001-06-01'))
                ->setTo(new DateTime('2001-07-01'))
                ->setCreated(new DateTime('2001-01-01 00:00:00'))
                ->setAffected(new DateTime('2001-01-01 00:00:00'))
                ->setType(Entry::SHOW)
                ->setBody(null)
                ->setBodyIs('is')
                ->setBodyEn('en')
                ->setOrientation('')
        ];
        $actual = $service->fetchList('2001');

        $this->assertEquals($expected, $actual);
    }

    public function testFeed()
    {
        $service = new EntryService($this->pdo);
        $expected = $service->fetchFeed();
        $this->assertCount(6, $expected);
    }

    public function testByType()
    {
        $service = new EntryService($this->pdo);
        $expected = $service->fetchByType(Entry::PROJECT);
        $this->assertCount(3, $expected);
    }

    public function testFetchByAfter()
    {
        $service = new EntryService($this->pdo);
        $expected = $service->fetchAfter(new DateTime('2019-12-31'));
        $this->assertCount(1, $expected);
    }

    public function testFetchAffected()
    {
        $service = new EntryService($this->pdo);
        $expected = $service->fetchAffected();
        $this->assertCount(6, $expected);
    }

    public function testFetchAll()
    {
        $service = new EntryService($this->pdo);
        $expected = $service->fetchAll();
        $this->assertCount(6, $expected);
    }

    public function testFetchYears()
    {
        $service = new EntryService($this->pdo);
        $expected = $service->fetchYears();
        $actual = [
            (object)['year' => '2020'],
            (object)['year' => '2010'],
            (object)['year' => '2001'],
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testUpdate()
    {
        $service = new EntryService($this->pdo);

        $entry = (new Entry())
            ->setId(1)
            ->setTitle('show number one')
            ->setFrom(new DateTime('2001-06-01'))
            ->setTo(new DateTime('2001-07-01'))
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setType(Entry::PROJECT)
            ->setBodyIs('<is>')
            ->setBodyEn('<en>')
            ->setOrientation('');

        $service->save($entry);

        $expected = [
            (object)[
                'id' => 1,
                'title' => 'show number one',
                'from' => '2001-06-01',
                'to' => '2001-07-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::PROJECT,
                'body_is' => '<is>',
                'body_en' => '<en>',
                'orientation' => '',
            ],
            (object)[
                'id' => 2,
                'title' => 'show #2',
                'from' => '2001-06-01',
                'to' => '2001-07-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::SHOW,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 3,
                'title' => 'show #3',
                'from' => '2010-01-01',
                'to' => '2010-02-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::PROJECT,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 4,
                'title' => 'show #4',
                'from' => '2010-01-15',
                'to' => '2010-02-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::SHOW,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 5,
                'title' => 'show #5',
                'from' => '2010-01-02',
                'to' => '2010-01-31',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::SHOW,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 6,
                'title' => 'show #6',
                'from' => '2020-01-01',
                'to' => '2020-01-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::PROJECT,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from Entry');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testSave()
    {
        $service = new EntryService($this->pdo);
        $entry = (new Entry)
            ->setTitle('new entry')
            ->setFrom(new DateTime('2001-06-01'))
            ->setTo(new DateTime('2001-07-01'))
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setType(Entry::PROJECT)
            ->setBodyIs('<is>')
            ->setBodyEn('<en>')
            ->setOrientation('');

        $id = $service->save($entry);

        $expected = [
            (object)[
                'id' => 1,
                'title' => 'show #1',
                'from' => '2001-06-01',
                'to' => '2001-07-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::PROJECT,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 2,
                'title' => 'show #2',
                'from' => '2001-06-01',
                'to' => '2001-07-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::SHOW,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 3,
                'title' => 'show #3',
                'from' => '2010-01-01',
                'to' => '2010-02-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::PROJECT,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 4,
                'title' => 'show #4',
                'from' => '2010-01-15',
                'to' => '2010-02-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::SHOW,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 5,
                'title' => 'show #5',
                'from' => '2010-01-02',
                'to' => '2010-01-31',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::SHOW,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => 6,
                'title' => 'show #6',
                'from' => '2020-01-01',
                'to' => '2020-01-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::PROJECT,
                'body_is' => 'is',
                'body_en' => 'en',
                'orientation' => '',
            ],
            (object)[
                'id' => $id,
                'title' => 'new entry',
                'from' => '2001-06-01',
                'to' => '2001-07-01',
                'created' => '2001-01-01 00:00:00',
                'affected' => '2001-01-01 00:00:00',
                'type' => Entry::PROJECT,
                'body_is' => '<is>',
                'body_en' => '<en>',
                'orientation' => '',
            ]
        ];

        $statement = $this->getDatabase()->prepare('select * from Entry');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testSaveWithAuthors()
    {
        $service = new EntryService($this->pdo);
        $entry = (new Entry)
            ->setTitle('new entry')
            ->setFrom(new DateTime('2001-06-01'))
            ->setTo(new DateTime('2001-07-01'))
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setType(Entry::PROJECT)
            ->setBodyIs('<is>')
            ->setBodyEn('<en>')
            ->setOrientation('')
            ->setAuthors([
                (new Author)
                    ->setId(1)
            ]);

        $id = $service->save($entry);

        $expacted = [
            (object)[
                'entry_id' => '1',
                'author_id' => '1',
                'order' => '1',
            ],
            (object)[
                'entry_id' => '1',
                'author_id' => '2',
                'order' => '2',
            ],
            (object)[
                'entry_id' => '4',
                'author_id' => '2',
                'order' => '1',
            ],
            (object)[
                'entry_id' => '6',
                'author_id' => '2',
                'order' => '1',
            ],
            (object)[
                'entry_id' => (string)$id,
                'author_id' => '1',
                'order' => '0',
            ],
        ];
        $statement = $this->getDatabase()->prepare('select * from Entry_has_Author');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expacted, $actual);
    }

    public function testSaveWithGallery()
    {
        $service = new EntryService($this->pdo);
        $entry = (new Entry)
            ->setId(1)
            ->setTitle('new entry')
            ->setFrom(new DateTime('2001-06-01'))
            ->setTo(new DateTime('2001-07-01'))
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setType(Entry::PROJECT)
            ->setBodyIs('<is>')
            ->setBodyEn('<en>')
            ->setOrientation('')
            ->setGallery([
                (new Image)
                    ->setId(1),
                (new Image)
                    ->setId(2)
            ]);

        $service->save($entry);

        $expacted = [
            (object)[ // 1
                'image_id' => '1',
                'entry_id' => '1',
                'order' => '0',
                'type' => '2',
            ],
            (object)[ // 1
                'image_id' => '2',
                'entry_id' => '1',
                'order' => '1',
                'type' => '2',
            ],
            (object)[ //2
                'image_id' => '1',
                'entry_id' => '4',
                'order' => '1',
                'type' => '1',
            ],
            (object)[ //3
                'image_id' => '2',
                'entry_id' => '4',
                'order' => '1',
                'type' => '2',
            ],
            (object)[ //4
                'image_id' => '1',
                'entry_id' => '6',
                'order' => '1',
                'type' => '1',
            ],
            (object)[ //5
                'image_id' => '2',
                'entry_id' => '6',
                'order' => '1',
                'type' => '2',
            ],

        ];
        $statement = $this->getDatabase()->prepare(
            'select * from Entry_has_Image order by entry_id, image_id, `type`, `order`'
        );
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expacted, $actual);
    }

    public function testSaveWithPoster()
    {
        $service = new EntryService($this->pdo);
        $entry = (new Entry)
            ->setId(1)
            ->setTitle('new entry')
            ->setFrom(new DateTime('2001-06-01'))
            ->setTo(new DateTime('2001-07-01'))
            ->setCreated(new DateTime('2001-01-01 00:00:00'))
            ->setAffected(new DateTime('2001-01-01 00:00:00'))
            ->setType(Entry::PROJECT)
            ->setBodyIs('<is>')
            ->setBodyEn('<en>')
            ->setOrientation('')
            ->setPosters([
                (new Image)
                    ->setId(1)]
            );

        $service->save($entry);

        $expacted = [
            (object)[ // 1
                'image_id' => '1',
                'entry_id' => '1',
                'order' => '0',
                'type' => '1',
            ],
            (object)[ //2
                'image_id' => '1',
                'entry_id' => '4',
                'order' => '1',
                'type' => '1',
            ],
            (object)[ //3
                'image_id' => '2',
                'entry_id' => '4',
                'order' => '1',
                'type' => '2',
            ],
            (object)[ //4
                'image_id' => '1',
                'entry_id' => '6',
                'order' => '1',
                'type' => '1',
            ],
            (object)[ //5
                'image_id' => '2',
                'entry_id' => '6',
                'order' => '1',
                'type' => '2',
            ],

        ];
        $statement = $this->getDatabase()->prepare(
            'select * from Entry_has_Image order by entry_id, image_id, `type`, `order`'
        );
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expacted, $actual);
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
