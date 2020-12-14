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
        $rows = App::$db->getRowsWhere('orders');

        foreach ($rows as $id => &$row) {

            $user = App::$db->getRowWhere('users', ['email' => $row['email']]);

            $row['user_name'] = $user['user_name'];

            $timestamp = date('Y-m-d H:i:s', $row['timestamp']);
            $difference = abs(strtotime("now") - strtotime($timestamp));
            $days = floor($difference / (3600 * 24));
            $hours = floor($difference / 3600);
            $minutes = floor(($difference - ($hours * 3600)) / 60);
            $result = "{$days}d {$hours}:{$minutes} H";

            $row['timestamp'] = $result;

            $statusForm = new OrderStatusForm($row['status'], $id);
            $row['status_form'] = $statusForm->render();

            unset($row['email'], $row['status']);
        }

        parent::__construct([
            'headers' => [
                'ID',
                'Pizza Name',
                'Time Ago',
                'User Name',
                'Status'
            ],
            'rows' => $rows
        ]);
    }

}