<?php
namespace App\Service;

use PDO;
use DateTime;

class Inventory
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
            select * from Inventory where id = :id
        ');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function fetch(string $id)
    {
        $statement = $this->pdo->prepare('
            select * from Inventory where id = :id
        ');
        $statement->execute(['id' => $id]);
        $item = $statement->fetch();

        $imagesStatement = $this->pdo->prepare('
            select I.* from Inventory_has_Image II
            join Image I on (I.id = II.image_id)
            where II.inventory_id = :id;
        ');

        $imagesStatement->execute(['id' => $item->id]);
        $item->images = $imagesStatement->fetchAll();

        return $item;
    }

    public function fetchList($lang = 'is'): array
    {
        $statement = $this->pdo->prepare(
            $lang === 'is'
                ? 'select *, body_is as `body` from `Inventory` order by `created` asc'
                : 'select *, body_en as `body` from `Inventory` order by `created` asc'
        );
        $statement->execute([]);

        $entries = $statement->fetchAll();

        $imagesStatement = $this->pdo->prepare('
            select I.* from Inventory_has_Image II
            join Image I on (I.id = II.image_id)
            where II.inventory_id = :id;
        ');

        return array_map(function ($entry) use ($imagesStatement) {
            $imagesStatement->execute(['id' => $entry->id]);
            $entry->images = $imagesStatement->fetchAll();
            return $entry;
        }, $entries);
    }

    public function fetchAffected(): array
    {
        $statement = $this->pdo->prepare('
            select * from `Inventory` order by affected desc limit 0, 6
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
          INSERT INTO `Inventory` ({$columns}) VALUES ({$values}) 
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();

    }

    public function delete(string $id): int
    {
        $statement = $this->pdo->prepare('delete from `Inventory` where id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }

    /**
     * Attach an array of ImageIDs to an entry.
     *
     * @param string $inventoryId
     * @param array $images
     */
    public function attachImages(string $inventoryId, array $images) {

        $deleteStatement = $this->pdo->prepare(
            'delete from Inventory_has_Image where inventory_id = :inventory_id'
        );
        $deleteStatement->execute(['inventory_id' => $inventoryId]);

        $insertStatement = $this->pdo->prepare('
            insert into Inventory_has_Image (`inventory_id`, `image_id`, `order`) 
            values (:inventory_id, :image_id, :order)
        ');

        foreach ($images as $count => $image) {
            $insertStatement->execute([
                'inventory_id' => $inventoryId,
                'image_id' => $image,
                'order' => $count
            ]);
        }
    }
}
