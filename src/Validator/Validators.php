<?php

namespace App\Validator;

/**
 * Responsável por validação de formato de dados
 */
abstract class Validators
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

    protected function email($value)
    {
        return (filter_var($value, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    protected function cpf($value)
    {
        $cpf = preg_replace('/[^0-9]/is', '', $value);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // perplexed õ.O
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    protected function telephone($value)
    {
        $telephone = preg_replace('/[^0-9]/is', '', $value);

        if (
            (strlen($telephone) != 11) &&
            (strlen($telephone) != 10)
        ) return false;

        return true;
    }
}
