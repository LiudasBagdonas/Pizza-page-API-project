<?php

namespace App\Views\Tables\User;

use App\App;
use Core\Views\Table;

class OrderTable extends Table
{
    public function __construct()
    {
        $orders = App::$db->getRowsWhere('orders', ['email' => $_SESSION['email']]);
        $rows = [];

        foreach ($orders as $id => &$order) {
            $rows[$id]['id'] = $id;
            $rows[$id]['status'] = $order['status'];

            $timeStamp = date('Y-m-d H:i:s', $order['timestamp']);
            $difference = abs(strtotime("now") - strtotime($timeStamp));
            $days = floor($difference / (3600 * 24));
            $hours = floor($difference / 3600);
            $minutes = floor(($difference - ($hours * 3600)) / 60);
            $result = "{$days}d {$hours}:{$minutes} H";
            $rows[$id]['timestamp'] = $result;
            $rows[$id]['pizza_name'] = $order['name'];

            unset($order['email']);
        }

        parent::__construct([
            'headers' => [
                'ID',
                'Status',
                'Time Ago',
                'Pizza Name'
            ],
            'rows' => $rows
        ]);
    }
}