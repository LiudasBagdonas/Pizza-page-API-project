<?php


namespace App\Views\Forms\Admin\Order;


use Core\Views\Form;

class OrderStatusForm extends Form
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
                'status' => [
                    'type' => 'select',
                    'options' => [
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled'
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
                    'type' => 'submit',
                ],
            ]
        ]);
    }
}