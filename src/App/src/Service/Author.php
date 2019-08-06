<?php
namespace App\Service;

use PDO;
use DateTime;

class Author
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $id): \stdClass
    {
        $statement = $this->pdo->prepare('
            select * from Author where id = :id
        ');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function fetchList(): array
    {
        $statement = $this->pdo->prepare('select * from `Author` order by `name` asc');
        $statement->execute([]);

        $list = $statement->fetchAll();

        $entriesStatement = $this->pdo->prepare('
            select E.* from Entry_has_Author EA 
                join Entry E on (E.id = EA.entry_id)
            where EA.author_id = :id;
        ');

        return array_map(function ($item) use ($entriesStatement) {
            $entriesStatement->execute(['id' => $item->id]);
            $item->entries = $entriesStatement->fetchAll();
            return $item;
        }, $list);
    }

    public function fetchAffected(): array
    {
        $statement = $this->pdo->prepare('
            select * from `Author` order by affected desc limit 0, 5
        ');
        $statement->execute([]);

        return $statement->fetchAll();
    }

    public function save(array $data): int {

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
          INSERT INTO `Author` ({$columns}) VALUES ({$values}) 
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();

    }

    public function search($query)
    {
        if (empty($query)) {
            return [];
        }

        $statement = $this->pdo->prepare('
            select * from Author where name like :query
        ');

        $statement->execute([':query' => "%{$query}%"]);

        return $statement->fetchAll();
    }

    public function delete(string $id): int
    {
        $statement = $this->pdo->prepare('delete from `Author` where id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }
}
