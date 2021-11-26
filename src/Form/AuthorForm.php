<?php

namespace App\Form;

use App\Form\Form;
use App\Model\Author;
use App\Filters\ToInt;
use Laminas\Filter\ToNull;
use Laminas\Validator\{Digits, Date};
use DateTime;

class AuthorForm extends Form
{
    public function getModel(): Author
    {
        $values = $this->inputFilter->getValues();

        return (new Author())
            ->setId($values['id'])
            ->setName($values['name'])
            ->setCreated($values['created'] ? new DateTime($values['created']) : null)
            ->setAffected(new DateTime($values['affected']))
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
            'name' => [
                'name' => 'name',
                'required' => true,
                'allow_empty' => false,
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
                'filters' => [
                    ['name' => ToNull::class]
                ],
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
