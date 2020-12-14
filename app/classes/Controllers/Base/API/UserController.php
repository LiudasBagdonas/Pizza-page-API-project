<?php

namespace App\Controllers\Base\API;

use App\App;

class UserController
{
    public function __construct()
    {
        if ((App::$session->getUser()['role'] ?? false) !== 'user') {
            header('HTTP/1.0 403 Forbidden', true, 403);
            exit();
        }
    }
}