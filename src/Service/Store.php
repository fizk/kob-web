<?php

namespace App\Service;

use App\Model;
use PDO;
use DateTime;

class Store
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(int $id): ?Model\Store
    {
        $statement = $this->pdo->prepare('
            select * from Store where id = :id
        ');
        $statement->execute(['id' => $id]);
        $store = $statement->fetch();
        return $store
            ? (new Model\Store)
            ->setId($store->id)
            ->setTitle($store->title)
            ->setCreated(new DateTime($store->created))
            ->setAffected(new DateTime($store->affected))
            ->setBodyIs($store->body_is)
            ->setBodyEn($store->body_en)
            : null;
    }

    public function fetchAll(string $language = 'is'): array
    {
        $statement = $this->pdo->prepare(
            $language == 'is'
                ? 'select *, body_is as `body` from Store'
                : 'select *, body_en as `body` from Store'
        );
        $authorsStatement = $this->pdo->prepare('
            select A.*, SA.order
                from Author A join Store_has_Author SA on (A.id = SA.author_id)
                where store_id = :id
                order by SA.order;
        ');
        $galleryStatement = $this->pdo->prepare('
            select I.*, SI.order
                from Image I join Store_has_Image SI on (I.id = SI.image_id)
                where store_id = :id
                order by SI.order;
        ');

        $statement->execute();

        return array_map(function ($store) use ($authorsStatement, $galleryStatement) {
            $authorsStatement->execute(['id' => $store->id]);
            $galleryStatement->execute(['id' => $store->id]);

            return (new Model\Store)
                ->setId($store->id)
                ->setTitle($store->title)
                ->setCreated(new DateTime($store->created))
                ->setAffected(new DateTime($store->affected))
                ->setBodyIs($store->body_is)
                ->setBodyEn($store->body_en)
                ->setBody($store->body)
                ->setAuthors(array_map(function ($object) {
                    return (new Model\Author)
                        ->setId($object->id)
                        ->setName($object->name)
                        ->setCreated(new DateTime($object->created))
                        ->setAffected(new DateTime($object->affected))
                        ->setOrder($object->order ?? null);
                }, $authorsStatement->fetchAll()))
                ->setGallery(array_map(function ($object) {
                    return (new Model\Image())
                        ->setId($object->id)
                        ->setName($object->name)
                        ->setDescription($object->description)
                        ->setSize($object->size)
                        ->setWidth($object->width)
                        ->setHeight($object->height)
                        ->setOrder($object->order ?? null)
                        ->setCreated(new DateTime($object->created))
                        ->setAffected(new DateTime($object->affected));
                }, $galleryStatement->fetchAll()));
        }, $statement->fetchAll());
    }

    public function fetch(int $id): ?Model\Store
    {
        $statement = $this->pdo->prepare('
            select * from Store where id = :id
        ');
        $statement->execute(['id' => $id]);
        $store = $statement->fetch();

        if (!$store) return null;

        $authorsStatement = $this->pdo->prepare('
            select A.*, SA.order
                from Author A join Store_has_Author SA on (A.id = SA.author_id)
                where store_id = :id
                order by SA.order;
        ');
        $galleryStatement = $this->pdo->prepare('
            select I.*, SI.order
                from Image I join Store_has_Image SI on (I.id = SI.image_id)
                where store_id = :id
                order by SI.order;
        ');

        $authorsStatement->execute(['id' => $store->id]);
        $galleryStatement->execute(['id' => $store->id]);

        return (new Model\Store)
            ->setId($store->id)
            ->setTitle($store->title)
            ->setCreated(new DateTime($store->created))
            ->setAffected(new DateTime($store->affected))
            ->setBodyIs($store->body_is)
            ->setBodyEn($store->body_en)
            ->setAuthors(array_map(function ($object) {
                return (new Model\Author)
                    ->setId($object->id)
                    ->setName($object->name)
                    ->setCreated(new DateTime($object->created))
                    ->setAffected(new DateTime($object->affected))
                    ->setOrder($object->order ?? null);
            }, $authorsStatement->fetchAll()))
            ->setGallery(array_map(function($object) {
                return (new Model\Image())
                    ->setId($object->id)
                    ->setName($object->name)
                    ->setDescription($object->description)
                    ->setSize($object->size)
                    ->setWidth($object->width)
                    ->setHeight($object->height)
                    ->setOrder($object->order ?? null)
                    ->setCreated(new DateTime($object->created))
                    ->setAffected(new DateTime($object->affected));
            }, $galleryStatement->fetchAll()));
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
          INSERT INTO `Store` ({$columns}) VALUES ({$values})
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function delete(string $id): int
    {
        $statement = $this->pdo->prepare('delete from `Store` where id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }
}
