<?php

namespace App\Controllers\Common;

use App\App;
use Core\FileDB;

class InstallController
{
    public function install()
    {
        App::$db = new FileDB(DB_FILE);

        App::$db->createTable('users');
        App::$db->insertRow('users', ['email' => 'test@test.lt', 'password' => 'test', 'user_name' => 'testas', 'role' => 'user']);
        App::$db->insertRow('users', ['email' => 'pica@skani.lt', 'password' => 'pica', 'user_name' => 'Picis', 'role' => 'admin']);
        App::$db->createTable('pizzas');
        App::$db->createTable('orders');
    }
}

