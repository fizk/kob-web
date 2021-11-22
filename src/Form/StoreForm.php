<?php

namespace App\Form;

use App\Form\Form;
use App\Filters\ToInt;
use Laminas\Filter\ToNull;
use Laminas\Validator\{Digits, Date};

class StoreForm extends Form
{
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
            'title' => [
                'name' => 'type',
                'required' => true,
                'allow_empty' => false,
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
            'affected' => [
                'name' => 'affected',
                'required' => true,
                'allow_empty' => false,
                'validators' => [
                    [
                        'name' => Date::class,
                        'options' => ['step' => 'any', 'format' => 'Y-m-d H:i:s']
                    ]
                ],
            ],
            'created' => [
                'name' => 'created',
                'required' => false,
                'allow_empty' => true,
                'validators' => [
                    [
                        'name' => Date::class,
                        'options' => ['step' => 'any', 'format' => 'Y-m-d H:i:s']
                    ]
                ],
            ],
        ];
    }
}
