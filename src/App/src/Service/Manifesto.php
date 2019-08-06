<?php
namespace App\Service;

use PDO;
use DateTime;

class Manifesto
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $id)
    {
        $entryStatement = $this->pdo->prepare('select * from `Manifesto` where id = :id');
        $entryStatement->execute(['id' => $id]);

        $manifesto =  $entryStatement->fetch();
        if (!$manifesto) {
            return null;
        }

        $galleryStatement = $this->pdo->prepare('
            select I.* from `Manifesto_has_Image` MI
                join `Image` I on (I.`id` = MI.`image_id`)
                where MI.entry_id = :id
        ');
        $galleryStatement->execute(['id' => $id]);
        $manifesto->gallery = $galleryStatement->fetchAll();

        return $manifesto;
    }

    public function fetch()
    {
        $entryStatement = $this->pdo->prepare('select * from `Manifesto`');
        $entryStatement->execute([]);

        return $entryStatement->fetchAll();
    }

    public function getByType($type, $lang = 'is')
    {
        $entryStatement = $this->pdo->prepare(
            $lang === 'is'
                ? 'select *, body_is as `body` from `Manifesto` where `type` = :type'
                : 'select *, body_en as `body` from `Manifesto` where `type` = :type'
        );
        $entryStatement->execute(['type' => $type]);

        $manifesto =  $entryStatement->fetch();
        if (!$manifesto) {
            return null;
        }
        $galleryStatement = $this->pdo->prepare('
            select I.* from `Manifesto_has_Image` MI
                join `Image` I on (I.`id` = MI.`image_id`)
                where MI.entry_id = :id
        ');
        $galleryStatement->execute(['id' => $manifesto->id]);


        $manifesto->gallery = $galleryStatement->fetchAll();

        return $manifesto;
    }

    public function attachImages(string $manifestoId, array $images): array {

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
          INSERT INTO `Manifesto` ({$columns}) VALUES ({$values}) 
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();

    }

}
