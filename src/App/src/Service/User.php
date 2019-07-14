<?php
namespace App\Service;

use PDO;

class User
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetch($name, $password)
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
