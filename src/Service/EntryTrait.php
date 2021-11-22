<?php
namespace App\Service;

use App\Model\Author;
use App\Model\Image;
use DateTime;

trait EntryTrait
{
    /**
     * Helper method that fetches all posters attached
     * to an Entry
     *
     * @param string $id
     * @return \stdClass|null
     */
    private function fetchPosters(string $id): ?Image
    {
        $posterStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 1
            order by EI.`order`;
        ');
        $posterStatement->execute(['id' => $id]);
        $posters = $posterStatement->fetch();
        return  $posters
            ? (new Image())
                ->setId($posters->id)
                ->setName($posters->name)
                ->setDescription($posters->description)
                ->setSize($posters->size)
                ->setWidth($posters->width)
                ->setHeight($posters->height)
                ->setOrder($posters->order ?? null)
                ->setCreated(new DateTime($posters->created))
                ->setAffected(new DateTime($posters->affected))
            : null;
    }

    /**
     * Helper method that fetches all gallery images attached
     * to an Entry
     *
     * @param string $id
     * @return \stdClass[]
     */
    private function fetchGallery(string $id): array
    {
        $galleryStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 2
            order by EI.`order`;
        ');
        $galleryStatement->execute(['id' => $id]);

        return array_map(function($object) {
            return (new Image())
                ->setId($object->id)
                ->setName($object->name)
                ->setDescription($object->description)
                ->setSize($object->size)
                ->setWidth($object->width)
                ->setHeight($object->height)
                ->setOrder($object->order ?? null)
                ->setCreated(new DateTime($object->created))
                ->setAffected(new DateTime($object->affected))
                ;
        }, $galleryStatement->fetchAll());

    }

    /**
     * Helper method that fetches all authors attached
     * to an Entry
     *
     * @param string $id
     * @return \stdClass[]
     */
    private function fetchAuthors(string $id): array
    {
        $authorStatement = $this->pdo->prepare('
            select A.* from Entry_has_Author EA
                join Author A on (A.id = EA.author_id)
            where EA.entry_id = :id
            order by EA.`order`;
        ');

        $authorStatement->execute(['id' => $id]);

        return array_map(function ($object) {
            return (new Author)
                ->setId($object->id)
                ->setName($object->name)
                ->setCreated(new DateTime($object->created))
                ->setAffected(new DateTime($object->affected))
                ->setOrder($object->order ?? null)
                ;
        }, $authorStatement->fetchAll());
    }
}
