<?php
namespace App\Service;

trait EntryTrait
{
    /**
     * Helper method that fetches all posters attached
     * to an Entry
     *
     * @param string $id
     * @return \stdClass|null
     */
    private function fetchPosters(string $id): ?\stdClass
    {
        $posterStatement = $this->pdo->prepare('
            select I.*, EI.`type` from Entry_has_Image EI
            join Image I on (I.id = EI.image_id)
            where EI.entry_id = :id and `type` = 1
            order by EI.`order`;
        ');
        $posterStatement->execute(['id' => $id]);
        $posters = $posterStatement->fetch();
        return  $posters ? $posters : null;
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
        return $galleryStatement->fetchAll();
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
        return $authorStatement->fetchAll();
    }
}
