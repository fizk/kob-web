<?php
namespace App\Service;

use App\Model\{Entry, Entries};
use PDO;
use DateTime;

class EntryService
{
    use EntryTrait;

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get one entry with additional data:
     * authors
     * posters
     * gallery
     */
    public function get(string $id): ?Entry
    {
        $statement = $this->pdo->prepare('select * from `Entry` where id = :id');
        $statement->execute(['id' => $id]);

        $entry = $statement->fetch();
        if (!$entry) {
            return null;
        }

        return (new Entry())
            ->setId($entry->id)
            ->setTitle($entry->title)
            ->setFrom(new DateTime($entry->from))
            ->setTo(new DateTime($entry->to))
            ->setCreated(new DateTime($entry->created))
            ->setAffected(new DateTime($entry->affected))
            ->setType($entry->type)
            ->setBodyIs($entry->body_is)
            ->setBodyEn($entry->body_en)
            ->setBody(null)
            ->setOrientation($entry->orientation)
            ->setAuthors($this->fetchAuthors($entry->id))
            ->setPosters($this->fetchPosters($entry->id))
            ->setGallery($this->fetchGallery($entry->id))
            ;
    }

    /**
     * Get one entry with previous and next entry,
     * in the correct language.
     *
     * Current entry includes:
     * authors
     * posters
     * gallery
     */
    public function fetch(string $id, string $lang = 'is'): ?Entries
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
        if (!$result['current']) {
            return null;
        }

        $entries = new Entries();

        if ($result['current']) {
            $entries->setCurrent((new Entry())
                ->setId($result['current']->id)
                ->setTitle($result['current']->title)
                ->setFrom(new DateTime($result['current']->from))
                ->setTo(new DateTime($result['current']->to))
                ->setCreated(new DateTime($result['current']->created))
                ->setAffected(new DateTime($result['current']->affected))
                ->setType($result['current']->type)
                ->setBodyIs($result['current']->body_is)
                ->setBodyEn($result['current']->body_en)
                ->setBody($result['current']->body)
                ->setOrientation($result['current']->orientation)
                ->setAuthors($this->fetchAuthors($result['current']->id))
                ->setPosters($this->fetchPosters($result['current']->id))
                ->setGallery($this->fetchGallery($result['current']->id)));
        }

        if ($result['previous']) {
            $entries->setPrevious((new Entry())
                ->setId($result['previous']->id)
                ->setTitle($result['previous']->title)
                ->setFrom(new DateTime($result['previous']->from))
                ->setTo(new DateTime($result['previous']->to))
                ->setCreated(new DateTime($result['previous']->created))
                ->setAffected(new DateTime($result['previous']->affected))
                ->setType($result['previous']->type)
                ->setBodyIs($result['previous']->body_is)
                ->setBodyEn($result['previous']->body_en)
                ->setBody($result['previous']->body)
                ->setOrientation($result['previous']->orientation)
                ->setAuthors($this->fetchAuthors($result['previous']->id))
                ->setPosters($this->fetchPosters($result['previous']->id))
                ->setGallery($this->fetchGallery($result['previous']->id)));
        }

        if ($result['next']) {
            $entries->setNext((new Entry())
                ->setId($result['next']->id)
                ->setTitle($result['next']->title)
                ->setFrom(new DateTime($result['next']->from))
                ->setTo(new DateTime($result['next']->to))
                ->setCreated(new DateTime($result['next']->created))
                ->setAffected(new DateTime($result['next']->affected))
                ->setType($result['next']->type)
                ->setBodyIs($result['next']->body_is)
                ->setBodyEn($result['next']->body_en)
                ->setBody($result['next']->body)
                ->setOrientation($result['next']->orientation)
                ->setAuthors($this->fetchAuthors($result['next']->id))
                ->setPosters($this->fetchPosters($result['next']->id))
                ->setGallery($this->fetchGallery($result['next']->id)));
        }

        return $entries;
    }

    /**
     * Fetch entries where the `from` is smaller then $date and `to` is bigger
     * that $date.
     *
     * @return App\Model\Entry[]
     */
    public function fetchCurrent(DateTime $date, string $language = 'is'): array
    {
        $statement = $this->pdo->prepare(
            $language == 'is'
            ? 'select *, body_is as body from Entry where `from` <= :date and `to` >= :date order by `from`;'
            : 'select *, body_en as body from Entry where `from` <= :date and `to` >= :date; order by `from`'
        );
        $statement->execute(['date' => $date->format('Y-m-d')]);

        return array_map(function ($item) {
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
                ->setBody($item->body)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id))
                ->setGallery($this->fetchGallery($item->id));
        }, $statement->fetchAll());
    }

    /**
     * Fetch latest entry (biggest `from` date) that is of `type` $type.
     * The result is returned as an array with one (or zero) item(s)
     *
     * @return App\Model\Entry[]
     */
    public function fetchLatestByType(string $type = Entry::SHOW, string $language = 'is'): array
    {
        $statement = $this->pdo->prepare(
            $language == 'is'
            ? 'select *, body_is as body from Entry where `type` = :type order by `from` desc limit 0, 1;'
            : 'select *, body_en as body from Entry where `type` = :type order by `from` desc limit 0, 1;'
        );
        $statement->execute(['type' => $type]);

        return array_map(function ($item) {
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
                ->setBody($item->body)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id))
                ->setGallery($this->fetchGallery($item->id));
        }, $statement->fetchAll());
    }

    /**
     * Fetch all entries. If year provided,
     * only these entries are included.
     *
     * @return App\Model\Entry[]
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

        return array_map(function ($item) {
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
                ->setBody($item->body ?? null)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id))
                ->setGallery($this->fetchGallery($item->id));
        }, $statement->fetchAll());
    }

    /**
     * Fetch 20 newest entries. This is mostly for the RSS feed.
     *
     * @return App\Model\Entry[]
     */
    public function fetchFeed(): array
    {
        $statement = $this->pdo->prepare('
          select * from `Entry` order by `affected` desc limit 0, 20
        ');
        $statement->execute([]);

        return array_map(function ($item) {
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
                ->setBody($item->body ?? null)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id))
                ->setGallery($this->fetchGallery($item->id));
        }, $statement->fetchAll());
    }

    /**
     * Fetch all entries of a specific type, like:
     * news, show, project...
     *
     * @return App\Model\Entry[]
     */
    public function fetchByType(string $type): array
    {
        $statement = $this->pdo->prepare('
            select *
            from `Entry`
            where type = :type
            order by `from` desc;
        ');
        $statement->execute(['type' => $type]);

        return array_map(function ($item) {
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
                ->setBody($item->body ?? null)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id))
                ->setGallery($this->fetchGallery($item->id));
        }, $statement->fetchAll());
    }

    /**
     * Fetch entries that are valid after a give date and are not NEWS,
     * in a given language.
     *
     * @return App\Model\Entry[]
     */
    public function fetchAfter(DateTime $date, string $language = 'is'): array
    {
        $statement = $this->pdo->prepare(
            $language == 'is'
                ? 'select *, body_is as body from Entry where `from` > :date and type != "news";'
                : 'select *, body_en as body from Entry where `from` > :date and type != "news";'
        );
        $statement->execute(['date' => $date->format('Y-m-d')]);

        return array_map(function ($item) {
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
                ->setBody($item->body ?? null)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id))
                ->setGallery($this->fetchGallery($item->id));
        }, $statement->fetchAll());
    }

    /**
     * Fetch 6 most recently affected entries.
     * Mostly for the Dashboard.
     *
     * @return App\Model\Entry[]
     */
    public function fetchAffected(): array
    {
        $statement = $this->pdo->prepare('
            select * from Entry
            order by affected desc limit 0, 6;
        ');
        $statement->execute([]);

        return array_map(function ($item) {
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
                ->setBody($item->body ?? null)
                ->setOrientation($item->orientation)
                ->setAuthors($this->fetchAuthors($item->id))
                ->setPosters($this->fetchPosters($item->id));
        }, $statement->fetchAll());
    }

    /**
     * Fetch a list of all 'years'.
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
     * @return App\Model\Entry[]
     */
    public function fetchAll(): array
    {
        $statement = $this->pdo->prepare('select * from `Entry` order by `from` desc');
        $statement->execute();
        return array_map(function ($item) {
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
                ->setBody($item->body ?? null)
                ->setOrientation($item->orientation);
        }, $statement->fetchAll());

        return $statement->fetchAll();
    }

    /**
     * Save an entry.
     */
    public function save(Entry $entry): int
    {
        $data = $entry->jsonSerialize();
        $authors = $data['authors'];
        $posters = $data['posters'];
        $gallery = $data['gallery'];

        unset($data['body']);
        unset($data['authors']);
        unset($data['posters']);
        unset($data['gallery']);

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
          INSERT INTO `Entry` ({$columns}) VALUES ({$values})
          on duplicate key update {$update};
        ");

        $statement->execute($data);
        $id = $entry->getId()
            ? $entry->getId()
            : $this->pdo->lastInsertId();

        $this->attachAuthors($id, $authors);
        $this->attachImages($id, $posters, 1);
        $this->attachImages($id, $gallery, 2);


        return $id;
    }

    /**
     * Delete an entry.
     */
    public function delete(string $id): int
    {
        $statement = $this->pdo->prepare('delete from `Entry` where id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }

    /**
     * Attach an array of AuthorIDs to an entry.
     */
    private function attachAuthors(string $id, array $authors)
    {
        $deleteStatement = $this->pdo->prepare('delete from Entry_has_Author where entry_id = :id');
        $deleteStatement->execute(['id' => $id]);

        $insertStatement = $this->pdo->prepare('
            insert into Entry_has_Author (`entry_id`, `author_id`, `order`) values (:entry, :author, :order)
        ');

        foreach ($authors as $count => $author) {
            $insertStatement->execute([
                'entry' => $id,
                'author' => $author->getId(),
                'order' => $count
            ]);
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
    private function attachImages(string $entryId, array $images, int $type = 1)
    {
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
                'image_id' => $image->getId(),
                'type' => $type,
                'order' => $count
            ]);
        }
    }
}
