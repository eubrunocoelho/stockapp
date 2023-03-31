<?php

namespace App\Validator;

abstract class DataValidator
{
    protected function required($value)
    {
        return (strlen($value) > 0) ? true : false;
    }
}
