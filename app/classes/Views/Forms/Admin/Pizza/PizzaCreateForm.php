<?php


namespace App\Views\Forms\Admin\Pizza;


use Core\Views\Form;

class PizzaCreateForm extends PizzaBaseForm
{
    public function __construct() {
        parent::__construct();

        $this->data['attr']['id'] = 'pizza-create-form';
        $this->data['buttons']['create'] = [
            'title' => 'Add',
        ];
    }
}