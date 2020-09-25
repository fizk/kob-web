<?php
namespace App\Service;

use PDO;
use DateTime;

class Image
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $id): \stdClass
    {
        $statement = $this->pdo->prepare('
            select * from Image where id = :id
        ');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
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
          INSERT INTO `Image` ({$columns}) VALUES ({$values})
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function updateDescription(string $id, string $description, DateTime $affected)
    {
        $statement = $this->pdo->prepare('
            update `Image` set description = :description, affected = :date where id = :id
        ');
        $statement->execute([
            'id' => $id,
            'description' => $description,
            'date' => $affected->format('Y-m-d H:i:s')
        ]);
        return $statement->rowCount();
    }
}
