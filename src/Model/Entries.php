<?php

namespace App\Model;

use JsonSerializable;

class Entries implements JsonSerializable {
    private ?Entry $previous = null;
    private ?Entry $current = null;
    private ?Entry $next = null;

    public function setPrevious(?Entry $previous): self
    {
        $this->previous = $previous;
        return $this;
    }

    public function getPrevious(): ?Entry
    {
        return $this->previous;
    }

    public function setCurrent(?Entry $current): self
    {
        $this->current = $current;
        return $this;
    }

    public function getCurrent(): ?Entry
    {
        return $this->current;
    }

    public function setNext(?Entry $next): self
    {
        $this->next = $next;
        return $this;
    }

    public function getNext(): ?Entry
    {
        return $this->next;
    }


    public function jsonSerialize(): array
    {
        return [
            'previous' => $this->previous,
            'current' => $this->current,
            'next' => $this->next,
        ];
    }
}
