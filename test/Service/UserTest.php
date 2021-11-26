<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;
use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;
use App\Model\{User};
use PDO;

class UserTest extends TestCase
{
    use MySQLTestCaseTrait;
    protected ?PDO $pdo = null;
    static protected $connection;

    public function testGet()
    {
        $service = new UserService($this->pdo);
        $expected = (new User())
            ->setId(1)
            ->setName('user1')
            ->setEmail('email1@service.com')
            ->setPassword('pass1')
            ->setType(1);

        $actual = $service->get('1');

        $this->assertEquals($expected, $actual);
    }

    public function testGetNotFound()
    {
        $service = new UserService($this->pdo);
        $expected = null;

        $actual = $service->get('123456789');

        $this->assertEquals($expected, $actual);
    }

    public function testFetch()
    {
        $service = new UserService($this->pdo);
        $expected = [
            (new User())
                ->setId(1)
                ->setName('user1')
                ->setEmail('email1@service.com')
                ->setPassword('pass1')
                ->setType(1),
            (new User())
                ->setId(2)
                ->setName('user2')
                ->setEmail('email2@service.com')
                ->setPassword('pass2')
                ->setType(2),
        ];

        $actual = $service->fetch();

        $this->assertEquals($expected, $actual);
    }

    public function testFetchByEmail()
    {
        $service = new UserService($this->pdo);
        $expected =
            (new User())
                ->setId(2)
                ->setName('user2')
                ->setEmail('email2@service.com')
                ->setPassword('pass2')
                ->setType(2)
            ;

        $actual = $service->fetchByEmail('email2@service.com');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchByEmailNotFound()
    {
        $service = new UserService($this->pdo);
        $expected = null;

        $actual = $service->fetchByEmail('not@an.email');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchByCredentials()
    {
        $service = new UserService($this->pdo);
        $expected =
            (new User())
                ->setId(2)
                ->setName('user2')
                ->setEmail('email2@service.com')
                ->setPassword('pass2')
                ->setType(2)
            ;

        $actual = $service->fetchByCredentials('email2@service.com', 'pass2');

        $this->assertEquals($expected, $actual);
    }

    public function testFetchByCredentialsNotFound()
    {
        $service = new UserService($this->pdo);
        $expected = null;

        $actual = $service->fetchByCredentials('not user', 'not password');

        $this->assertEquals($expected, $actual);
    }

    public function testUpdate()
    {
        $service = new UserService($this->pdo);

        $service->save([
            'id' => '1',
            'name' => 'new name',
            'password' => 'pass1',
            'email' => 'email1@service.com',
            'type' => 1,
        ]);

        $expected = [
            (object)[
                'id' => '1',
                'name' => 'new name',
                'password' => 'pass1',
                'email' => 'email1@service.com',
                'type' => 1,
            ],
            (object)[
                'id' => '2',
                'name' => 'user2',
                'password' => 'pass2',
                'email' => 'email2@service.com',
                'type' => 2,
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from User');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    public function testSave()
    {
        $service = new UserService($this->pdo);

        $service->save([
            'id' => 'newid',
            'name' => 'created new name',
            'password' => 'createdpass1',
            'email' => 'createdemail1@service.com',
            'type' => 1,
        ]);

        $expected = [
            (object)[
                'id' => '1',
                'name' => 'user1',
                'password' => 'pass1',
                'email' => 'email1@service.com',
                'type' => 1,
            ],
            (object)[
                'id' => '2',
                'name' => 'user2',
                'password' => 'pass2',
                'email' => 'email2@service.com',
                'type' => 2,
            ],
            (object)[
                'id' => 'newid',
                'name' => 'created new name',
                'password' => 'createdpass1',
                'email' => 'createdemail1@service.com',
                'type' => 1,
            ],
        ];

        $statement = $this->getDatabase()->prepare('select * from User');
        $statement->execute();
        $actual = $statement->fetchAll();

        $this->assertEquals($expected, $actual);
    }

    protected function tearDown(): void
    {
        try {
            $this->getDatabase()->exec('SET foreign_key_checks=0');
            $this->getDatabase()->exec('truncate table User');
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
            'User' => [
                [
                    'id' => '1',
                    'name' => 'user1',
                    'password' => 'pass1',
                    'email' => 'email1@service.com',
                    'type' => 1,
                ],
                [
                    'id' => '2',
                    'name' => 'user2',
                    'password' => 'pass2',
                    'email' => 'email2@service.com',
                    'type' => 2,
                ],
            ],
        ]);
    }
}
