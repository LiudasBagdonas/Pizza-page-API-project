<?php


namespace App\Views\Forms\Admin\Pizza;


use Core\Views\Form;

class PizzaUpdateForm extends PizzaBaseForm
{
    public function __construct()
    {
        parent::__construct();

        $this->data['attr']['id'] = 'pizza-update-form';
        $this->data['buttons']['update'] = [
            'title' => 'Update',
        ];
    }

}