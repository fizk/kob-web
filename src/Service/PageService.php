<?php
namespace App\Service;

use App\Model\{Image, Page};
use PDO;
use DateTime;

class PageService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $id): ?Page
    {
        $entryStatement = $this->pdo->prepare('select * from `Manifesto` where id = :id');
        $entryStatement->execute(['id' => $id]);

        $page =  $entryStatement->fetch();

        if (!$page) {
            return null;
        }

        $galleryStatement = $this->pdo->prepare('
            select I.* from `Manifesto_has_Image` MI
                join `Image` I on (I.`id` = MI.`image_id`)
                where MI.entry_id = :id
        ');
        $galleryStatement->execute(['id' => $id]);

        return (new Page)
            ->setId($page->id)
            ->setType($page->type)
            ->setBodyIs($page->body_is)
            ->setBodyEn($page->body_en)
            ->setGallery(array_map(function ($item) {
                return (new Image())
                    ->setId($item->id)
                    ->setName($item->name)
                    ->setDescription($item->description)
                    ->setSize($item->size)
                    ->setWidth($item->width)
                    ->setHeight($item->height)
                    ->setOrder($item->order ?? null)
                    ->setCreated(new DateTime($item->created))
                    ->setAffected(new DateTime($item->affected));
            }, $galleryStatement->fetchAll()));
    }

    public function fetch(): array
    {
        $entryStatement = $this->pdo->prepare('select * from `Manifesto`');
        $entryStatement->execute([]);

        return array_map(function ($item) {
            return (new Page)
                ->setId($item->id)
                ->setType($item->type)
                ->setBodyIs($item->body_is)
                ->setBodyEn($item->body_en);
        }, $entryStatement->fetchAll());
    }

    public function getByType($type, $lang = 'is'): ?Page
    {
        $entryStatement = $this->pdo->prepare(
            $lang === 'is'
                ? 'select *, body_is as `body` from `Manifesto` where `type` = :type'
                : 'select *, body_en as `body` from `Manifesto` where `type` = :type'
        );
        $entryStatement->execute(['type' => $type]);

        $page =  $entryStatement->fetch();
        if (!$page) {
            return null;
        }
        $galleryStatement = $this->pdo->prepare('
            select I.* from `Manifesto_has_Image` MI
                join `Image` I on (I.`id` = MI.`image_id`)
                where MI.entry_id = :id
        ');
        $galleryStatement->execute(['id' => $page->id]);

        return (new Page)
            ->setId($page->id)
            ->setType($page->type)
            ->setBody($page->body)
            ->setBodyIs($page->body_is)
            ->setBodyEn($page->body_en)
            ->setGallery(array_map(function ($item) {
                return (new Image())
                    ->setId($item->id)
                    ->setName($item->name)
                    ->setDescription($item->description)
                    ->setSize($item->size)
                    ->setWidth($item->width)
                    ->setHeight($item->height)
                    ->setOrder($item->order ?? null)
                    ->setCreated(new DateTime($item->created))
                    ->setAffected(new DateTime($item->affected));
            }, $galleryStatement->fetchAll()));
    }

    public function attachImages(string $manifestoId, array $images): array
    {

        $deleteStatement = $this->pdo->prepare(
            'delete from `Manifesto_has_Image` where entry_id = :manifesto_id'
        );
        $deleteStatement->execute(['manifesto_id' => $manifestoId]);

        $insertStatement = $this->pdo->prepare('
            insert into Manifesto_has_Image (`image_id`, `entry_id`, `order`)
            values (:image_id, :manifesto_id, :order)
        ');

        foreach ($images as $count => $image) {
            $insertStatement->execute([
                'image_id' => $image,
                'manifesto_id' => $manifestoId,
                'order' => $count
            ]);
        }

        return [];
    }

    public function save(Page $page): int
    {
        $data = $page->jsonSerialize();
        unset($data['body']);
        unset($data['gallery']);

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
          INSERT INTO `Manifesto` ({$columns}) VALUES ({$values})
          on duplicate key update {$update};
        ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();
    }
}
