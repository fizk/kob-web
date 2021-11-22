<?php

namespace App\Model;

use DateTime;
use JsonSerializable;

class Author implements JsonSerializable
{
    private ?int $id = null;
    private string $name;
    private DateTime $created;
    private DateTime $affected;
    private ?int $order = null;
    private array $entries = [];

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

    public function setCreated(DateTime $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setAffected(DateTime $affected): self
    {
        $this->affected = $affected;
        return $this;
    }

    public function getAffected(): DateTime
    {
        return $this->affected;
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

    public function setEntries(array $entries): self
    {
        $this->entries = $entries;
        return $this;
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created' => $this->created,
            'affected' => $this->affected,
            'order' => $this->order,
            'entries' => $this->entries,
        ];
    }
}
