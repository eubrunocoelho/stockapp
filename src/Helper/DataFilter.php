<?php

namespace App\Helper;

class DataFilter
{
    public static function isInteger($data)
    {
        if ($data != '') $data = intval($data);
        elseif ($data == '') $data = null;

        return $data;
    }
}
