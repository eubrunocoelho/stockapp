<?php

namespace App\Helper;

class DataFilter
{
    public static function isString($data)
    {
        if ($data != '') $data = strval(trim($data));
        elseif ($data == '') $data = null;

        return $data;
    }

    public static function isInteger($data)
    {
        if ($data != '') $data = intval(trim($data));
        elseif ($data == '') $data = null;

        return $data;
    }
}
