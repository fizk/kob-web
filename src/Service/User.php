<?php
namespace App\Service;

use PDO;

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get one User
     *
     * @param string $id
     * @return \stdClass
     */
    public function get(string $id): \stdClass
    {
        $statement = $this->pdo->prepare('select * from `User` where id = :id');
        $statement->execute(['id' => $id]);

        return $statement->fetch();
    }

    /**
     * Get all Users
     *
     * @param string $id
     * @return \stdClass
     */
    public function fetch(): array
    {
        $statement = $this->pdo->prepare('select * from `User`');
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Save a User.
     *
     * @param array $data
     * @return int
     */
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

    /**
     * Delete a User.
     *
     * @param string $id
     * @return int affected rows
     */
    public function delete(string $id): int
    {
        $statement = $this->pdo->prepare('delete from `User` where id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }

    public function fetchByEmail($email)
    {
        $statement = $this->pdo->prepare('
          select * from `User` where `email` = :email
        ');
        $statement->execute([
            'email' => $email,
        ]);

        return $statement->fetch();
    }

    public function fetchByCredentials($name, $password)
    {
        $statement = $this->pdo->prepare('
          select * from `User` where `name` = :name and `password` = :password
        ');
        $statement->execute([
            'name' => $name,
            'password' => $password,
        ]);

        return $statement->fetch();
    }
}
