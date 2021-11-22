<?php

namespace App\Model;

use App\Model\Image;
use DateTime;
use DateTimeInterface;
use JsonSerializable;

class Entry implements JsonSerializable {
    private ?int $id = null;
    private string $title;
    private DateTime $from;
    private DateTime $to;
    private DateTime $created;
    private DateTime $affected;
    private string $type;
    private string $body_is;
    private ?string $body = null;
    private string $body_en;
    private string $orientation;
    private array $authors = [];
    private ?Image $poster = null;
    private array $gallery = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getFrom(): DateTime
    {
        return $this->from;
    }

    public function setFrom(DateTime $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getTo(): DateTime
    {
        return $this->to;
    }

    public function setTo(DateTime $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getAffected(): DateTime
    {
        return $this->affected;
    }

    public function setAffected(DateTime $affected): self
    {
        $this->affected = $affected;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getBodyIs(): string
    {
        return $this->body_is;
    }

    public function setBodyIs(string $body): self
    {
        $this->body_is = $body;
        return $this;
    }

    public function getBodyEn(): string
    {
        return $this->body_en;
    }

    public function setBodyEn(string $body): self
    {
        $this->body_en = $body;
        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function getOrientation(): string
    {
        return $this->orientation;
    }

    public function setOrientation(string $orientation): self
    {
        $this->orientation = $orientation;
        return $this;
    }

    public function setAuthors(array $authors): self
    {
        $this->authors = $authors;
        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setPoster(?Image $poster): self
    {
        $this->poster = $poster;
        return $this;
    }

    public function getPoster(): ?Image
    {
        return $this->poster;
    }

    public function setGallery(array $gallery): self
    {
        $this->gallery = $gallery;
        return $this;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'from' => $this->from?->format(DateTimeInterface::ISO8601),
            'to' => $this->to?->format(DateTimeInterface::ISO8601),
            'created' => $this->created?->format(DateTimeInterface::ISO8601),
            'affected' => $this->affected?->format(DateTimeInterface::ISO8601),
            'type' => $this->type,
            'body_is' => $this->body_is,
            'body' => $this->body,
            'body_en' => $this->body_en,
            'orientation' => $this->orientation,
        ];
    }
}
