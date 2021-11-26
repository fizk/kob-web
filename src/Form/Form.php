<?php

namespace App\Form;

use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\InputFilter\InputFilterInterface;

abstract class Form implements InputFilterProviderInterface
{
    private array $data = [];
    private ?bool $valid = null;
    private Factory $factory;
    protected InputFilterInterface $inputFilter;

    public function __construct()
    {
        $this->factory = new Factory();
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->inputFilter->getValues();
    }

    abstract public function getModel();

    public function isValid(): bool
    {
        if ($this->valid !== null) {
            return $this->valid;
        }

        $this->inputFilter = $this->factory->createInputFilter(
            $this->getInputFilterSpecification()
        );

        $this->inputFilter->setData($this->data);

        $this->valid = $this->inputFilter->isValid();
        return $this->valid;
    }

    public function getMessages(): array
    {
        return $this->inputFilter
            ? $this->inputFilter?->getMessages()
            : [];
    }

    abstract public function getInputFilterSpecification(): array;
}
