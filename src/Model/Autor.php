<?php

namespace App\Model;

class Autor
{
    private
        $ID, $nome;

    public function setID($ID)
    {
        $this->ID = $ID;
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }
}
