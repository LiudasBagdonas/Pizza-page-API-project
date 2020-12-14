<?php


namespace App\Views\Forms\Admin\Order;


use Core\Views\Form;

class OrderCreateForm extends Form
{
    public function __construct($value = null)
    {
        parent::__construct([
            'attr' => [
                'method' => 'POST'
            ],
            'fields' => [
                'row_id' => [
                    'type' => 'hidden',
                    'value' => 'ORDER'
                ],
                'name' => [
                    'type' => 'hidden',
                    'value' => $value
                ],
            ],
            'buttons' => [
                'submit' => [
                    'title' => 'Order',
                    'type' => 'submit',
                ],
            ]
        ]);
    }
}


