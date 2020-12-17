<?php


namespace App\Controllers\Admin\API;

use App\App;
use App\Views\BasePage;
use App\Views\Forms\Admin\Order\OrderStatusForm;
use App\Views\Forms\Admin\Pizza\PizzaCreateForm;
use App\Views\Forms\Admin\Pizza\PizzaUpdateForm;
use Core\Api\Response;
use App\Controllers\Admin\OrdersController;
use Core\View;
use Core\Views\Link;

class OrdersApiController extends OrdersController
{
    public function index()
    {
        $forms = [
            'status' => (new OrderStatusForm())->render(),
        ];

        $content = (new View([
            'title' => 'Orderinhos',
            'js' => '/media/js/admin/order.js',
            'forms' => $forms ?? [],
        ]))->render(ROOT . '/app/templates/content/adminOrders.tpl.php');

        $this->page->setContent($content);

        return $this->page->render();
    }

    public function edit(): string
    {
        // This is a helper class to make sure
        // we use the same API json response structure
        $response = new Response();

        $orders = App::$db->getRowsWhere('orders');
        $data = [];
//        $data['headers'] = [
//            'ID',
//            'Status',
//            'Pizza Name',
//            'Time',
//            'Edit'
//        ];
        foreach ($orders as $order_id => $order) {
            $data[$order_id]['id'] = $order_id;
            $data[$order_id]['status'] = $order['status'];
            $data[$order_id]['pizza_name'] = $order['name'];
            $data[$order_id]['timestamp'] = $order['timestamp'];
            $data[$order_id]['buttons']['edit'] = 'Edit';
        }

        // Setting "what" to json-encode
        $response->setData($data);


        // Returns json-encoded response
        return $response->toJson();
    }
}