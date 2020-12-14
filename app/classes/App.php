<?php

namespace App;

use Core\FileDB;
use Core\Router;
use Core\Session;


class App
{
    public static FileDB $db;
    public static Session $session;
    public static Router $router;

    public function __construct()
    {
        self::$db = new FileDB(DB_FILE);
        self::$db->load();

        self::$session = new Session();
        self::$router = new Router();
    }

    public function run()
    {
        print self::$router::run();
    }

    public function __destruct()
    {
        self::$db->save();
    }
}