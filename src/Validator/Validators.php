<?php

namespace App\Validator;

use PDO;

abstract class Validators
{
    private
        $container, $database;

    protected function __construct($container)
    {
        $this->container = $container;
        $this->database = $this->container->get(PDO::class);
    }
    
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
        return (filter_var(trim($value), FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    protected function cpf($value)
    {
        $cpf = preg_replace('/[^0-9]/is', '', trim($value));

        if (strlen(trim($cpf)) != 11) return false;

        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        // perplexed õ.O
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) $d += $cpf[$c] * (($t + 1) - $c);

            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }

        return true;
    }

    protected function unique($value, $rules)
    {
        $rules = explode('|', $rules);
        $value = trim($value);

        $SQL =
            'SELECT * FROM ' . $rules[1] . ' 
             WHERE ' . $rules[0] . ' = :value';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':value', $value);
        $stmt->execute();

        return (!$stmt->rowCount() > 0) ? true : false;
    }

    protected function uniqueForUpdate($value, $rules)
    {
        $rules = explode('|', $rules);
        $value = trim($value);

        $SQL =
            'SELECT * FROM ' . $rules[1] . ' 
             WHERE ID != ' . $rules[2] . ' AND ' . $rules[0] . ' = :value';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':value', $value);
        $stmt->execute();

        return (!$stmt->rowCount() > 0) ? true : false;
    }
}
