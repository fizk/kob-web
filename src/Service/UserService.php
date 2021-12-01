<?php
namespace App\Service;

use App\Model\{User};
use PDO;

class UserService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $id): ?User
    {
        $statement = $this->pdo->prepare(
            'select * from `User` where id = :id'
        );
        $statement->execute(['id' => $id]);

        $object = $statement->fetch();
        return $object
            ? (new User())
                ->setId($object->id)
                ->setName($object->name)
                ->setPassword($object->password)
                ->setEmail($object->email)
                ->setType($object->type)
            : null;
    }

    public function fetch(): array
    {
        $statement = $this->pdo->prepare('select * from `User`');
        $statement->execute();

        return array_map(function ($object) {
            return (new User())
                ->setId($object->id)
                ->setName($object->name)
                ->setPassword($object->password)
                ->setEmail($object->email)
                ->setType($object->type);
        }, $statement->fetchAll());
    }

    public function save(array $data): int
    {
        $columns = implode(',', array_map(function ($i) {
            return " `{$i}`";
        }, array_keys($data)));

        $values = implode(',', array_map(function ($i) {
            return " :{$i}";
        }, array_keys($data)));
        $update = implode(', ', array_map(function ($i) {
            return "`{$i}` = :{$i}";
        }, array_keys($data)));

        $statement = $this->pdo->prepare("
          INSERT INTO `User` ({$columns}) VALUES ({$values})
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function delete(string $id): int
    {
        $statement = $this->pdo->prepare('delete from `User` where id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }

    public function fetchByEmail(string $email): ?User
    {
        $statement = $this->pdo->prepare('
          select * from `User` where `email` = :email
        ');
        $statement->execute([
            'email' => $email,
        ]);

        $object = $statement->fetch();
        return $object
                ? (new User())
                ->setId($object->id)
                ->setName($object->name)
                ->setPassword($object->password)
                ->setEmail($object->email)
                ->setType($object->type)
            : null;
    }

    public function fetchByCredentials(string $email, string $password): ?User
    {
        $statement = $this->pdo->prepare('
          select * from `User` where `email` = :email and `password` = :password
        ');
        $statement->execute([
            'email' => $email,
            'password' => $password,
        ]);

        $object = $statement->fetch();
        return $object
            ? (new User())
                ->setId($object->id)
                ->setName($object->name)
                ->setPassword($object->password)
                ->setEmail($object->email)
                ->setType($object->type)
            : null;
    }
}
