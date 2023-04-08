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

    protected function min($value, $ruleValue)
    {
        return (!(strlen(trim($value)) < $ruleValue)) ? true : false;
    }

    protected function max($value, $ruleValue)
    {
        return (!(strlen(trim($value)) > $ruleValue)) ? true : false;
    }

    protected function regex($value, $ruleValue)
    {
        return (preg_match($ruleValue, trim($value))) ? true : false;
    }
}
