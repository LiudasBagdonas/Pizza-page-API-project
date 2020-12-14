<?php

namespace App\Views\Forms\Common\Auth;

use Core\Views\Form;

class LoginForm extends Form
{
public function __construct()
{
    parent::__construct([
        'attr' => [
            'method' => 'POST',
        ],
        'fields' => [
            'email' => [
                'label' => 'EMAIL',
                'type' => 'text',
                'validators' => [
                    'validate_field_not_empty',
                    'validate_email',
                ],
                'extra' => [
                    'attr' => [
                        'placeholder' => 'Įvesk emailą',
                        'class' => 'input-field',
                    ],
                ],
            ],
            'password' => [
                'label' => 'PASSWORD',
                'type' => 'text',
                'validators' => [
                    'validate_field_not_empty',
                ],
                'extra' => [
                    'attr' => [
                        'placeholder' => 'Įvesk slaptažodį',
                        'class' => 'input-field',
                    ],
                ],
            ],
        ],
        'buttons' => [
            'send' => [
                'title' => 'LOGIN',
                'type' => 'submit',
                'extra' => [
                    'attr' => [
                        'class' => 'btn',
                    ],
                ],
            ],
        ],
        'validators' => [
            'validate_login' => [
                'email',
                'password',
            ]
        ]
    ]);
}
}