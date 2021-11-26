<?php
namespace App\Service;

use App\Model\{Entry, Author};
use PDO;
use DateTime;

class AuthorService
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
    public function get(string $id): ?Author
    {
        $statement = $this->pdo->prepare('
            select * from Author where id = :id
        ');
        $statement->execute(['id' => $id]);
        $author = $statement->fetch();
        return $author
            ? (new Author)
                ->setId($author->id)
                ->setName($author->name)
                ->setCreated(new DateTime($author->created))
                ->setAffected(new DateTime($author->affected))
            : null;
    }

    /**
     * Fetch one Author and all the Entries assosiated
     * with that person
     */
    public function fetch(string $id): ?Author
    {
        $statement = $this->pdo->prepare('
            select * from Author where id = :id
        ');
        $statement->execute(['id' => $id]);
        $item = $statement->fetch();

        if (!$item) {
            return null;
        }

        $author = (new Author)
            ->setId($item->id)
            ->setName($item->name)
            ->setCreated(new DateTime($item->created))
            ->setAffected(new DateTime($item->affected))
            ->setOrder($item->order ?? null)
            ;

        $entriesStatement = $this->pdo->prepare('
            select E.* from Entry_has_Author EA
                join Entry E on (E.id = EA.entry_id)
            where EA.author_id = :id;
        ');

        $entriesStatement->execute(['id' => $item->id]);
        $item->entries = $entriesStatement->fetchAll();

        $author->setEntries(array_map(function ($item) {
            return (new Entry())
                ->setId($item->id)
                ->setTitle($item->title)
                ->setFrom(new DateTime($item->from))
                ->setTo(new DateTime($item->to))
                ->setCreated(new DateTime($item->created))
                ->setAffected(new DateTime($item->affected))
                ->setType($item->type)
                ->setBodyIs($item->body_is)
                ->setBodyEn($item->body_en)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id));
            return $item;
        }, $item->entries));

        return $author;
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

        return array_map(function ($author) use ($entriesStatement) {
            $entriesStatement->execute(['id' => $author->id]);
            return (new Author)
                ->setId($author->id)
                ->setName($author->name)
                ->setCreated(new DateTime($author->created))
                ->setAffected(new DateTime($author->affected))
                ->setEntries(array_map(function ($item) {
                    return (new Entry())
                        ->setId($item->id)
                        ->setTitle($item->title)
                        ->setFrom(new DateTime($item->from))
                        ->setTo(new DateTime($item->to))
                        ->setCreated(new DateTime($item->created))
                        ->setAffected(new DateTime($item->affected))
                        ->setType($item->type)
                        ->setBodyIs($item->body_is)
                        ->setBodyEn($item->body_en)
                        ->setOrientation($item->orientation)
                        ->setAuthors($this->fetchAuthors($item->id))
                        ->setPosters($this->fetchPosters($item->id))
                    ;
                }, $entriesStatement->fetchAll()));
        }, $list);
    }

    public function fetchAffected(): array
    {
        $statement = $this->pdo->prepare('
            select * from `Author` order by affected desc limit 0, 6
        ');
        $statement->execute([]);

        return array_map(function ($author) {
            return (new Author)
                ->setId($author->id)
                ->setName($author->name)
                ->setCreated(new DateTime($author->created))
                ->setAffected(new DateTime($author->affected));
        }, $statement->fetchAll());
    }

    public function save(Author $author): int
    {
        $data = $author->jsonSerialize();
        unset($data['entries']);
        unset($data['order']);

        if (!$data['created']) {
            unset($data['created']);
        }

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
