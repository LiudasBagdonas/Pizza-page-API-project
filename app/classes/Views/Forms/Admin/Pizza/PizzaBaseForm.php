<?php


namespace App\Views\Forms\Admin\Pizza;


use Core\Views\Form;

class PizzaBaseForm extends Form
{
    public function __construct() {
        parent::__construct([
            'fields' => [
                'name' => [
                    'label' => 'PIZZA NAME',
                    'type' => 'text',
                    'validators' => [
                        'validate_field_not_empty',
                    ],
                    'extra' => [
                        'attr' => [
                            'placeholder' => 'Enter item\'s name',
                        ],
                    ],
                ],
                'price' => [
                    'label' => 'PRICE',
                    'type' => 'text',
                    'validators' => [
                        'validate_field_not_empty',
                        'validate_numeric',
                        'validate_field_range' => [
                            'min' => 1,
                            'max' => 9999,
                        ]
                    ],
                    'extra' => [
                        'attr' => [
                            'placeholder' => 'Enter pizzas price',
                        ],
                    ],
                ],
                'image' => [
                    'label' => 'IMAGE URL',
                    'type' => 'text',
                    'validators' => [
                        'validate_field_not_empty',
                        'validate_url',
                    ],
                    'extra' => [
                        'attr' => [
                            'placeholder' => 'Enter pizzas image URL',
                        ],
                    ],
                ],
            ],
            // No buttons since they will be defined in Extends
        ]);
    }
}