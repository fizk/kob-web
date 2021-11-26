<?php

namespace App\Model;

use DateTime;
use JsonSerializable;

class Image implements JsonSerializable
{
    private ?int $id = null;
    private string $name;
    private ?string $description = null;
    private int $size = 0;
    private int $width = 0;
    private int $height = 0;
    private ?int $order = null;
    private ?DateTime $created = null;
    private ?DateTime $affected = null;

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setCreated(?DateTime $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    public function setAffected(?DateTime $affected): self
    {
        $this->affected = $affected;
        return $this;
    }

    public function getAffected(): ?DateTime
    {
        return $this->affected;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'order' => $this->order,
            'created' => $this->created?->format('Y-m-d H:i:s'),
            'affected' => $this->affected?->format('Y-m-d H:i:s'),
        ];
    }
}
