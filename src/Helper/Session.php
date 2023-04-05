<?php

namespace App\Helper;

class Session
{
    public static function create($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function isTrue($name)
    {
        return ($_SESSION[$name] === true) ? true : false;
    }
}
