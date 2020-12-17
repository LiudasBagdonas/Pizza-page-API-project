<?php

namespace App\Views\Tables\Admin;

use App\App;
use App\Views\Forms\Admin\Order\OrderStatusForm;
use Core\Views\Table;

class OrdersTable extends Table
{
    protected OrderStatusForm $form;

    public function __construct()
    {
        $this->form = new OrderStatusForm();
        $orders = App::$db->getRowsWhere('orders');
        $rows = [];
        $this->form = new OrderStatusForm();

        foreach ($orders as $id => &$order) {

            $rows[$id]['id'] = $id;
            $user = App::$db->getRowWhere('users', ['email' => $order['email']]);

            $rows[$id]['user_name'] = $user['user_name'];
            $rows[$id]['pizza_name'] = $order['name'];

            $timestamp = date('Y-m-d H:i:s', $order['timestamp']);
            $difference = abs(strtotime("now") - strtotime($timestamp));
            $days = floor($difference / (3600 * 24));
            $hours = floor($difference / 3600);
            $minutes = floor(($difference - ($hours * 3600)) / 60);
            $result = "{$days}d {$hours}:{$minutes} H";

            $rows[$id]['timestamp'] = $result;
            $rows[$id]['status'] = $order['status'];

//            $rows[$id]['action'] = $this->form->render();

//            $statusForm = new OrderStatusForm($order['status'], $id);
//            $row['status_form'] = $statusForm->render();

            unset($order['email']);
        }

        parent::__construct([
            'headers' => [
                'ID',
                'User Name',
                'Pizza Name',
                'Time Ago',
                'Status',
                'Action'
            ],
            'rows' => $rows
        ]);
    }

}