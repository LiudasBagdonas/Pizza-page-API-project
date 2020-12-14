<?php

namespace App\Views\Forms\Admin\User;


use Core\Views\Form;

class UserRoleForm extends Form
{
    public function __construct($value = null, $id = null)
    {
        parent::__construct([
            'attr' => [
                'method' => 'POST'
            ],
            'fields' => [
                'row_id' => [
                    'type' => 'hidden',
                    'value' => $id
                ],
                'role' => [
                    'type' => 'select',
                    'options' => [
                        'admin' => 'Admin',
                        'user' => 'User'
                    ],
                    'validators' => [
                        'validate_select',
                    ],
                    'value' => $value,
                ]
            ],
            'buttons' => [
                'submit' => [
                    'title' => 'Set',
                ],
            ]
        ]);
    }

}