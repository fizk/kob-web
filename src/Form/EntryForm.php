<?php

namespace App\Form;

use App\Filters\ArrayFilter;
use App\Form\Form;
use App\Filters\ToInt;
use App\Model\{Author, Entry, Image};
use Laminas\Filter\ToNull;
use Laminas\Validator\{Digits, Date};
use DateTime;

class EntryForm extends Form
{
    public function getModel(): Entry
    {
        $values = $this->inputFilter->getValues();

        return (new Entry())
            ->setId($values['id'])
            ->setTitle($values['title'])
            ->setFrom(new DateTime($values['from']))
            ->setTo(new DateTime($values['to']))
            ->setCreated($values['created'] ? new DateTime($values['created']) : null)
            ->setAffected(new DateTime($values['affected']))
            ->setBodyIs($values['body_is'])
            ->setBodyEn($values['body_en'])
            ->setOrientation($values['orientation'])
            ->setType($values['type'])
            ->setAuthors(array_map(function ($author) {
                return (new Author())
                    ->setId($author);
            }, $values['authors']))
            ->setGallery(array_map(function ($image) {
                return (new Image)
                    ->setId($image);
            }, $values['gallery']))
            ->setPosters(array_map(function ($image) {
                return (new Image)
                    ->setId($image);
            }, $values['posters']))
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
            'from' => [
                'name' => 'from',
                'required' => true,
                'allow_empty' => false,
                'validators' => [
                    [
                        'name' => Date::class,
                        'options' => ['step' => 'any', 'format' => 'Y-m-d']
                    ]
                ],
            ],
            'to' => [
                'name' => 'to',
                'required' => true,
                'allow_empty' => false,
                'validators' => [
                    [
                        'name' => Date::class,
                        'options' => ['step' => 'any', 'format' => 'Y-m-d']
                    ]
                ],
            ],
            'type' => [
                'name' => 'type',
                'required' => true,
                'allow_empty' => false,
            ],
            'orientation' => [
                'name' => 'type',
                'required' => true,
                'allow_empty' => false,
            ],
            'authors' => [
                'name' => 'type',
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    [
                        'name' => ArrayFilter::class
                    ],
                ],
            ],
            'posters' => [
                'name' => 'type',
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    [
                        'name' => ArrayFilter::class
                    ],
                ],
            ],
            'gallery' => [
                'name' => 'type',
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    [
                        'name' => ArrayFilter::class
                    ],
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
