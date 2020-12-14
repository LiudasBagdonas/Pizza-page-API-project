<?php

namespace Core;

use App\App;
use DateTime;

/** Iki galo nebaigta cookies class */

class Cookies
{
    public $name;
    public $value;
    public $time;

    public function __construct($name)
    {
        $this->cookieName($name);
    }

    /**
     * set cookie name;
     *
     * @param $name_given
     */
    public function cookieName($name_given)
    {
        $this->name = $name_given;
    }

    /**
     * set cookie value
     *
     * @param $value_given
     */
    public function cookieValue($value_given)
    {
        $this->value = $value_given;

    }

    /**
     * set cookie time
     *
     * @param $time_given
     */
    public function cookieTime($time_given)
    {
        $this->time = $time_given;
    }

    /**
     *
     * setcookie for user for example
     *
     */
    public function set()
    {
        setcookie($this->name, $this->value, $this->time);

    }

    /**
     * get created cookie
     *
     * @return array|mixed
     */
    public function getCookie()
    {
        $cookie = $_COOKIE[$this->name] ?? [];

        return $cookie;
    }

    /**
     *
     * delete cookie
     *
     */
    public function unset()
    {
        setcookie($this->name, null,  - 1);

    }




}
//class Cookies {
//    /**
//     * Cookie name - the name of the cookie.
//     * @var bool
//     */
//    private $name = false;
//
//    /**
//     * Cookie value
//     * @var string
//     */
//    private $value = "";
//
//    /**
//     * Cookie life time
//     * @var DateTime
//     */
//    private $time;
//
//    /**
//     * Cookie life time
//
//     */
//    private $user_id;
//
//
//
//    /**
//     * Constructor
//     */
//    public function __construct() {
//
//    }
//
//    /**
//     * Create or Update cookie.
//     */
//    public function create() {
//        return setcookie($this->name, $this->getValue(), $this->getTime(),  true);
//    }
//
//    /**
//     * Return a cookie
//     * @return mixed
//     */
//    public function get(){
//        return $_COOKIE[$this->getName()];
//    }
//
//    /**
//     * Delete cookie.
//     * @return bool
//     */
//    public function delete(){
//        return setcookie($this->name, '', time() - 3600, true);
//    }
//
//    /**
//     * @param $id
//     */
//    public function setName($id) {
//        $this->name = $id;
//    }
//
//    /**
//     * @return bool
//     */
//    public function getName() {
//        return $this->name;
//    }
//
//
//    /**
//     * @param $time
//     */
//    public function setTime($time) {
//        // Create a date
//        $date = new DateTime();
//        // Modify it (+1hours; +1days; +20years; -2days etc)
//        $date->modify($time);
//        // Store the date in UNIX timestamp.
//        $this->time = $date->getTimestamp();
//    }
//
//    /**
//     * @return bool|int
//     */
//    public function getTime() {
//        return $this->time;
//    }
//
//    /**
//     * @param string $value
//     */
//    public function setValue($value) {
//        $this->value = $value;
//    }
//
//    /**
//     * @return string
//     */
//    public function getValue() {
//        return $this->value;
//    }
//
//}



