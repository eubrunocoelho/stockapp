<?php

namespace App\Validator;

/**
 * Responsável por validação de formato de dados
 */
abstract class Validator
{
    /**
     * Verifica se o valor não está vazio
     */
    protected function required($value)
    {
        return (strlen(trim($value)) > 0) ? true : false;
    }
}
