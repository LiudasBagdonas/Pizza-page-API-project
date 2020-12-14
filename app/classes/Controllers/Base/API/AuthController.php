<?php

namespace App\Controllers\Base\API;

use App\App;

class AuthController
{
    public function __construct()
    {
        if (!App::$session->getUser()) {
            header('HTTP/1.0 403 Forbidden', true, 403);
            exit();
        }
    }
}