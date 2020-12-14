<?php

namespace App\Controllers\Common\Auth;

use App\App;

class LogoutController
{
    public function logout()
    {
        App::$session->logout('/login');
    }

}