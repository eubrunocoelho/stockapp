<?php

namespace App\Helper;

class Session
{
    public static function create($index, $value)
    {
        $_SESSION[$index] = $value;
    }
}
