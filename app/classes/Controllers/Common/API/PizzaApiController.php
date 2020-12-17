<?php

namespace App\Controllers\Common\API;

use App\App;
use App\Controller;
use App\Views\Forms\Admin\Pizza\PizzaCreateForm;
use App\Views\Forms\Admin\Pizza\PizzaUpdateForm;
use Core\Api\Response;

class PizzaApiController
{

    public function index(): string
    {
        // This is a helper class to make sure
        // we use the same API json response structure
        $response = new Response();

        $role = App::$session->getUser() ? App::$session->getUser()['role'] : null;
        $pizzas = App::$db->getRowsWhere('pizzas');

        foreach ($pizzas as $row_id => &$pizza) {
            // We must add this, so JS can assign the id
            $pizza['id'] = $row_id;

            if ($role == 'admin') {
                $pizza['buttons']['delete'] = 'Delete';
                $pizza['buttons']['edit'] = 'Edit';
            } elseif ($role === 'user') {
                $pizza['buttons']['order'] = 'Order';
            }
        }

        // Setting "what" to json-encode
        $response->setData($pizzas);

        // Returns json-encoded response
        return $response->toJson();
    }

}






