<?php

namespace App\Model;

use JsonSerializable;

class Page implements JsonSerializable
{
    private ?int $id = null;
    private string $type;
    private ?string $body_is = null;
    private ?string $body_en = null;
    private ?string $body = null;
    private ?array $gallery = [];


    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getBodyIs(): ?string
    {
        return $this->body_is;
    }

    public function setBodyIs(?string $body): self
    {
        $this->body_is = $body;
        return $this;
    }

    public function getBodyEn(): ?string
    {
        return $this->body_en;
    }

    public function setBodyEn(?string $body): self
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
            'type' => $this->type,
            'body_is' => $this->body_is,
            'body_en' => $this->body_en,
            'body' => $this->body,
            'gallery' => $this->gallery,
        ];
    }
}
