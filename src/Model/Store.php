<?php

namespace App\Model;

use DateTime;
use JsonSerializable;

class Store implements JsonSerializable
{
    private ?int $id = null;
    private string $title;
    private ?DateTime $created = null;
    private ?DateTime $affected = null;
    private ?string $body = null;
    private string $body_is;
    private string $body_en;
    private array $authors = [];
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

    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    public function setCreated(?DateTime $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getAffected(): ?DateTime
    {
        return $this->affected;
    }

    public function setAffected(?DateTime $affected): self
    {
        $this->affected = $affected;
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

    public function setAuthors(array $authors): self
    {
        $this->authors = $authors;
        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
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
            'id' => $this->id = null,
            'title' => $this->title,
            'created' => $this->created?->format('Y-m-d H:i:s'),
            'affected' => $this->affected?->format('Y-m-d H:i:s'),
            'body' => $this->body = null,
            'body_is' => $this->body_is,
            'body_en' => $this->body_en,
            'authors' => $this->authors,
            'gallery' => $this->gallery,
        ];
    }
}
