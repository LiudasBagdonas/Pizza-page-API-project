<?php


namespace App\Controllers\User\API;


use App\App;
use App\Controllers\Base\UserController;
use App\Views\BasePage;
use App\Controllers\User\OrdersController;
use App\Views\Forms\Admin\Order\OrderCreateForm;
use App\Views\Forms\Admin\Pizza\PizzaCreateForm;
use Core\Api\Response;

class OrdersApiController extends UserController
{
    protected BasePage $page;

    public function __construct()
    {
        parent::__construct();
        $this->page = new BasePage([
            'title' => 'Orders',
        ]);
    }

//    public function index()
//    {
//
//    }

    public function create(): string
    {

        // This is a helper class to make sure
        // we use the same API json response structure
        $response = new Response();

        $id = $_POST['id'] ?? null;

        if ($id === null || $id == 'undefined') {
            $response->appendError('ApiController could not create, since ID is not provided! Check JS!');
        } else {
            $response->setData([
                'id' => $id
            ]);
            $row = App::$db->getRowById('pizzas', $id);
            unset($row['image'], $row['price']);
            App::$db->insertRow('orders', $row + ['status' => 'active', 'timestamp' => time(),
                    'email' => $_SESSION['email']]);
        }

        // Returns json-encoded response
        return $response->toJson();


    }
}