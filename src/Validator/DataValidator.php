<?php

namespace App\Validator;

/**
 * Responsável por validação de formato de dados
 */
abstract class DataValidator
{
    /**
     * Verifica se o valor não está vazio
     */
    protected function required($value)
    {
        return (strlen($value) > 0) ? true : false;
    }
}
