<?php

namespace App\Helper;

class Session
{
    public static function create($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function destroy()
    {
        session_destroy();
    }
}
