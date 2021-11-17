<?php
namespace App\Service;

use PDO;

class Author
{
    use EntryTrait;

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get on Author in its basic form
     */
    public function get(string $id): ?\stdClass
    {
        $statement = $this->pdo->prepare('
            select * from Author where id = :id
        ');
        $statement->execute(['id' => $id]);
        $author = $statement->fetch();
        return $author ? $author : null;
    }

    /**
     * Fetch one Author and all the Entries assosiated
     * with that person
     */
    public function fetch(string $id): ?\stdClass
    {
        $statement = $this->pdo->prepare('
            select * from Author where id = :id
        ');
        $statement->execute(['id' => $id]);
        $item = $statement->fetch();

        if (!$item) return null;

        $entriesStatement = $this->pdo->prepare('
            select E.* from Entry_has_Author EA
                join Entry E on (E.id = EA.entry_id)
            where EA.author_id = :id;
        ');

        $entriesStatement->execute(['id' => $item->id]);
        $item->entries = $entriesStatement->fetchAll();

        $item->entries = array_map(function ($item) {
            $item->authors = $this->fetchAuthors($item->id);
            $item->poster = $this->fetchPosters($item->id);
            // $item->gallery = $this->fetchGallery($item->id);
            $item->gallery = [];

            return $item;
        }, $item->entries);

        return $item;
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
            select * from `Author` order by affected desc limit 0, 6
        ');
        $statement->execute([]);

        return $statement->fetchAll();
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
          INSERT INTO `Author` ({$columns}) VALUES ({$values})
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function search($query): array
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
