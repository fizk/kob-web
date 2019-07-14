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

    public function get()
    {
        $entryStatement = $this->pdo->prepare('select * from `Manifesto`');
        $entryStatement->execute([]);

        $galleryStatement = $this->pdo->prepare('
            select I.* from `Manifesto_has_Image` MI
                join `Image` I on (I.`id` = MI.`image_id`)
        ');
        $galleryStatement->execute([]);

        $manifesto =  $entryStatement->fetch();
        $manifesto->gallery = $galleryStatement->fetchAll();

        return $manifesto;

    }

    public function attachImages(string $manifestoId, array $images): array {

        $deleteStatement = $this->pdo->prepare(
            'delete from `Manifesto_has_Image` where manifesto_id = :manifesto_id'
        );
        $deleteStatement->execute(['manifesto_id' => $manifestoId]);

        $insertStatement = $this->pdo->prepare('
            insert into Manifesto_has_Image (`image_id`, `manifesto_id`, `order`) 
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
