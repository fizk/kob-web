<?php
namespace App\Service;

use PDO;
use DateTime;

class Entry
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get one entry with additional data:
     * authors
     * posters
     * gallery
     *
     * @param string $id
     * @return \stdClass
     */
    public function get(string $id): \stdClass
    {
        $statement = $this->pdo->prepare('select * from `Entry` where id = :id');
        $statement->execute(['id' => $id]);

        $entry = $statement->fetch();

        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id;
        ');

        $authorStatement->execute(['id' => $entry->id]);
        $entry->authors = $authorStatement->fetchAll();

        $posterStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 1;
        ');
        $posterStatement->execute(['id' => $entry->id]);
        $entry->poster = $posterStatement->fetch();

        $galleryStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 2;
        ');
        $galleryStatement->execute(['id' => $entry->id]);
        $entry->gallery = $galleryStatement->fetchAll();

        return $entry;
    }

    /**
     * Get one entry with previous and next entry,
     * in the correct language.
     *
     * Current entry includes:
     * authors
     * posters
     * gallery
     *
     * @param string $id
     * @param string $lang
     * @return array|null
     */
    public function fetch(string $id, $lang = 'is'): ?array
    {
        $statement = $this->pdo->prepare(
            $lang === 'is'
                ? 'select *, body_is as `body` from `Entry` order by `from` desc'
                : 'select *, body_en as `body` from `Entry` order by `from` desc'
        );
        $statement->execute();

        $result = [
            'previous' => null,
            'current' => null,
            'next' => null,
        ];

        while (($item = $statement->fetch()) !== false) {
            if ($item->id === $id) {
                $result['current'] = $item;
                $result['next'] = $statement->fetch();
                break;
            } else {
                $result['previous'] = $item;
            }
        }

        if ($result['current'] === null) {
            return null;
        }

        if ($result['current']) {

            $authorStatement = $this->pdo->prepare('
                select A.* from Entry_has_Author EA
                    join Author A on (A.id = EA.author_id)
                where EA.entry_id = :id;
            ');

            $authorStatement->execute(['id' => $result['current']->id]);
            $result['current']->authors = $authorStatement->fetchAll();

            $posterStatement = $this->pdo->prepare('
                select I.*, EI.`type` from Entry_has_Image EI
                join Image I on (I.id = EI.image_id)
                where EI.entry_id = :id and `type` = 1;
            ');
            $posterStatement->execute(['id' => $result['current']->id]);
            $result['current']->poster = $posterStatement->fetch();

            $galleryStatement = $this->pdo->prepare('
                select I.*, EI.`type` from Entry_has_Image EI
                join Image I on (I.id = EI.image_id)
                where EI.entry_id = :id and `type` = 2;
            ');
            $galleryStatement->execute(['id' => $result['current']->id]);
            $result['current']->gallery = $galleryStatement->fetchAll();
        }

        if ($result['previous']) {

            $authorStatement = $this->pdo->prepare('
                select A.* from Entry_has_Author EA
                    join Author A on (A.id = EA.author_id)
                where EA.entry_id = :id;
            ');

            $authorStatement->execute(['id' => $result['previous']->id]);
            $result['previous']->authors = $authorStatement->fetchAll();

            $posterStatement = $this->pdo->prepare('
                select I.*, EI.`type` from Entry_has_Image EI
                join Image I on (I.id = EI.image_id)
                where EI.entry_id = :id and `type` = 1;
            ');
            $posterStatement->execute(['id' => $result['previous']->id]);
            $result['previous']->poster = $posterStatement->fetch();
        }

        if ($result['next']) {

            $authorStatement = $this->pdo->prepare('
                select A.* from Entry_has_Author EA
                    join Author A on (A.id = EA.author_id)
                where EA.entry_id = :id;
            ');

            $authorStatement->execute(['id' => $result['next']->id]);
            $result['next']->authors = $authorStatement->fetchAll();

            $posterStatement = $this->pdo->prepare('
                select I.*, EI.`type` from Entry_has_Image EI
                join Image I on (I.id = EI.image_id)
                where EI.entry_id = :id and `type` = 1;
            ');
            $posterStatement->execute(['id' => $result['next']->id]);
            $result['next']->poster = $posterStatement->fetch();
        }

        return $result;
    }

    /**
     * Fetch all entries. If year provided,
     * only these entries are included.
     *
     * Entries include:
     * authors
     * posters
     * gallery
     *
     * @param null|string $year
     * @return array
     */
    public function fetchList(?string $year = null): array
    {
        if ($year) {
            $statement = $this->pdo->prepare('
                select * 
                from `Entry`
                where year(`from`) = :year
                order by `from` desc;
            ');
            $statement->execute(['year' => $year]);
        } else {
            $statement = $this->pdo->prepare('select * from `Entry` order by `from` desc');
            $statement->execute([]);
        }

        $list = $statement->fetchAll();

        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id;
        ');
        $posterStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 1;
        ');
        $galleryStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 2;
        ');

        return array_map(function ($item) use ($authorStatement, $posterStatement, $galleryStatement) {

            $authorStatement->execute(['id' => $item->id]);
            $item->authors = $authorStatement->fetchAll();

            $posterStatement->execute(['id' => $item->id]);
            $item->poster = $posterStatement->fetch();

            $galleryStatement->execute(['id' => $item->id]);
            $item->gallery = $galleryStatement->fetchAll();

            return $item;

        }, $list);
    }

    /**
     * Fetch 20 newest entries. This is mostly for the RSS feed.
     *
     * Entries include:
     * authors
     * posters
     * gallery
     *
     * @return array
     */
    public function fetchFeed(): array
    {
        $statement = $this->pdo->prepare('
          select * from `Entry` order by `affected` desc limit 0, 20
        ');
        $statement->execute([]);


        $list = $statement->fetchAll();

        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id;
        ');
        $posterStatement = $this->pdo->prepare('
                select I.*, EI.`type` from Entry_has_Image EI
                join Image I on (I.id = EI.image_id)
                where EI.entry_id = :id and `type` = 1;
            ');
        $galleryStatement = $this->pdo->prepare('
                select I.*, EI.`type` from Entry_has_Image EI
                join Image I on (I.id = EI.image_id)
                where EI.entry_id = :id and `type` = 2;
            ');

        return array_map(function ($item) use ($authorStatement, $posterStatement, $galleryStatement) {

            $authorStatement->execute(['id' => $item->id]);
            $item->authors = $authorStatement->fetchAll();

            $posterStatement->execute(['id' => $item->id]);
            $item->poster = $posterStatement->fetch();

            $galleryStatement->execute(['id' => $item->id]);
            $item->gallery = $galleryStatement->fetchAll();

            return $item;

        }, $list);
    }

    /**
     * Fetch all entries of a specific type, like:
     * news, show, project...
     *
     * Entries include:
     * authors
     * posters
     * gallery
     *
     * @param $type
     * @return array
     */
    public function fetchByType($type)
    {
        $statement = $this->pdo->prepare('
            select * 
            from `Entry`
            where type = :type
            order by `from` desc;
        ');
        $statement->execute(['type' => $type]);

        $list = $statement->fetchAll();

        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id;
        ');
        $posterStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 1;
        ');
        $galleryStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 2;
        ');

        return array_map(function ($item) use ($authorStatement, $posterStatement, $galleryStatement) {
            $authorStatement->execute(['id' => $item->id]);
            $item->authors = $authorStatement->fetchAll();

            $posterStatement->execute(['id' => $item->id]);
            $item->poster = $posterStatement->fetch();

            $galleryStatement->execute(['id' => $item->id]);
            $item->gallery = $galleryStatement->fetchAll();

            return $item;

        }, $list);
    }

    /**
     * Fetch entries that are active for a given date, in a given language.
     *
     * Entries include:
     * authors
     * posters
     * gallery
     *
     * @param DateTime $date
     * @param string $language
     * @return array
     */
    public function fetchByDate(DateTime $date, $language = 'is'): array
    {
        $statement = $this->pdo->prepare(
            $language == 'is'
                ? 'select *, body_is as body from Entry where `from` <= :date and `to` >= :date;'
                : 'select *, body_en as body from Entry where `from` <= :date and `to` >= :date;'
        );
        $statement->execute(['date' => $date->format('Y-m-d')]);

        $list = $statement->fetchAll();

        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id;
        ');
        $posterStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 1;
        ');
        $galleryStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 2;
        ');

        return array_map(function ($item) use ($authorStatement, $posterStatement, $galleryStatement) {

            $authorStatement->execute(['id' => $item->id]);
            $item->authors = $authorStatement->fetchAll();

            $posterStatement->execute(['id' => $item->id]);
            $item->poster = $posterStatement->fetch();

            $galleryStatement->execute(['id' => $item->id]);
            $item->gallery = $galleryStatement->fetchAll();

            return $item;

        }, $list);
    }

    /**
     * Fetch entries that are valid after a give date and are not NEWS,
     * in a given language.
     *
     * Entries include:
     * authors
     * posters
     * gallery
     *
     * @param DateTime $date
     * @param string $language
     * @return array
     */
    public function fetchAfter(DateTime $date, $language = 'is'): array
    {
        $statement = $this->pdo->prepare(
            $language == 'is'
                ? 'select *, body_is as body from Entry where `from` > :date and type != "news";'
                : 'select *, body_en as body from Entry where `from` > :date and type != "news";'
        );
        $statement->execute(['date' => $date->format('Y-m-d')]);

        $list = $statement->fetchAll();

        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id;
        ');
        $posterStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 1;
        ');
        $galleryStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 2;
        ');

        return array_map(function ($item) use ($authorStatement, $posterStatement, $galleryStatement) {

            $authorStatement->execute(['id' => $item->id]);
            $item->authors = $authorStatement->fetchAll();

            $posterStatement->execute(['id' => $item->id]);
            $item->poster = $posterStatement->fetch();

            $galleryStatement->execute(['id' => $item->id]);
            $item->gallery = $galleryStatement->fetchAll();

            return $item;

        }, $list);
    }

    /**
     * Fetch 6 most recently affected entries.
     * Mostly for the Dashboard.
     *
     * @return array
     */
    public function fetchAffected(): array
    {
        $statement = $this->pdo->prepare('
            select * from Entry
            order by affected desc limit 0, 6;
        ');
        $statement->execute([]);

        $list = $statement->fetchAll();

        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id;
        ');

        return array_map(function ($item) use ($authorStatement) {

            $authorStatement->execute(['id' => $item->id]);
            $item->authors = $authorStatement->fetchAll();

            $posterStatement = $this->pdo->prepare('
                select I.*, EI.`type` from Entry_has_Image EI
                join Image I on (I.id = EI.image_id)
                where EI.entry_id = :id and `type` = 1;
            ');
            $posterStatement->execute(['id' => $item->id]);
            $item->poster = $posterStatement->fetch();

            $item->gallery = [];

            return $item;

        }, $list);

    }

    /**
     * Fetch a list of all 'years'.
     *
     * @return array
     */
    public function fetchYears(): array
    {
        $statement = $this->pdo->prepare('
            select year(`from`) as `year` 
            from `Entry` 
            group by `year` 
            order by `year` desc;
        ');
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Fetch all entries.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        $statement = $this->pdo->prepare('select * from `Entry` order by `from` desc');
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Save an entry.
     *
     * @param array $data
     * @return int
     */
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
          INSERT INTO `Entry` ({$columns}) VALUES ({$values}) 
          on duplicate key update {$update};
          ");

        $statement->execute($data);

        return $this->pdo->lastInsertId();

    }

    /**
     * Attach an array of AuthorIDs to an entry.
     *
     * @param string $id
     * @param array $authors
     */
    public function attachAuthors(string $id, array $authors) {

        $deleteStatement = $this->pdo->prepare('delete from Entry_has_Author where entry_id = :id');
        $deleteStatement->execute(['id' => $id]);

        $insertStatement = $this->pdo->prepare('
            insert into Entry_has_Author (`entry_id`, `author_id`, `order`) values (:entry, :author, :order)
        ');

        foreach ($authors as $count => $author) {
            $insertStatement->execute(['entry' => $id, 'author' => $author, 'order' => $count]);
        }
    }

    /**
     * Attach an array of ImageIDs to an entry.
     *
     * Additional parameter describes the type of image:
     * 1 = poster
     * 2 = gallery
     *
     * @param string $entryId
     * @param array $images
     * @param int $type
     */
    public function attachImages(string $entryId, array $images, int $type = 1) {

        $deleteStatement = $this->pdo->prepare(
            'delete from Entry_has_Image where entry_id = :entry_id and type = :type'
        );
        $deleteStatement->execute(['entry_id' => $entryId, 'type' => $type]);

        $insertStatement = $this->pdo->prepare('
            insert into Entry_has_Image (`entry_id`, `image_id`, `type`, `order`) 
            values (:entry_id, :image_id, :type, :order)
        ');

        foreach ($images as $count => $image) {
            $insertStatement->execute([
                'entry_id' => $entryId,
                'image_id' => $image,
                'type' => $type,
                'order' => $count
            ]);
        }
    }

    /**
     * Delete an entry.
     *
     * @param string $id
     * @return int affected rows
     */
    public function delete(string $id): int
    {
        $statement = $this->pdo->prepare('delete from `Entry` where id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }
}
