<?php

namespace App\Controllers\Base\API;

use App\App;

class AdminController
{
    public function __construct()
    {
        if ((App::$session->getUser()['role'] ?? false) !== 'admin') {
            header('HTTP/1.0 403 Forbidden', true, 403);
            exit();
        }
    }
}