<?php

namespace App\Helper;

class Input
{
    /**
     * Responsavel por persistir os dados do formulário
     */
    public static function getPersistValues($values)
    {
        // Percorre os valores, se esse existir retorna o valor, se não existir retorna `null`
        foreach ($values as $key => $value) {
            if ($value != '') $values[$key] = $value;
            else $values[$key] = null;
        }

        return $values;
    }

    public static function getPersistUpdateValues($request, $data)
    {
    }
}
