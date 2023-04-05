<?php

namespace App\Helper;

class Session
{
    public static function create($index, $value)
    {
        $_SESSION[$index] = $value;
    }

    public static function exists($index)
    {
        return (isset($_SESSION[$index])) ? true : false;
    }
}
