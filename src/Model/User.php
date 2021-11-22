<?php

namespace App\Model;

use JsonSerializable;

class User implements JsonSerializable
{
    private ?int $id;
    private string $name;
    private ?string $password;
    private ?string $email;
    private int $type;

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

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' =>  $this->id,
            'name' =>  $this->name,
            'password' =>  $this->password,
            'email' =>  $this->email,
            'type' =>  $this->type,
        ];
    }
}
