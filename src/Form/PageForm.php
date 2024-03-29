<?php

namespace App\Form;

use App\Form\Form;
use App\Filters\ToInt;
use App\Model\Page;
use Laminas\Filter\ToNull;
use Laminas\Validator\{Digits};

class PageForm extends Form
{
    public function getModel(): Page
    {
        $values = $this->inputFilter->getValues();

        return (new Page())
            ->setId($values['id'])
            ->setType($values['type'])
            ->setBodyIs($values['body_is'])
            ->setBodyEn($values['body_en'])
            ;
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    [
                        'name' => ToNull::class,
                        'options' => ['type' => 'all']
                    ],
                    [
                        'name' => ToInt::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => Digits::class
                    ]
                ],
            ],
            'body_is' => [
                'name' => 'body_is',
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    [
                        'name' => ToNull::class,
                        'options' => ['type' => 'all']
                    ]
                ],
            ],
            'body_en' => [
                'name' => 'body_en',
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    [
                        'name' => ToNull::class,
                        'options' => ['type' => 'all']
                    ]
                ],
            ],
            'type' => [
                'name' => 'type',
                'required' => true,
                'allow_empty' => false,
            ],
        ];
    }
}
