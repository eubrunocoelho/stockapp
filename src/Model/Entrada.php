<?php

namespace App\Model;

class Entrada
{
    private
        $ID, $ID_livro, $ID_gestor, $quantidade, $registrado_em;

    public function setID($ID)
    {
        $this->ID = $ID;
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setIDLivro($ID_livro)
    {
        $this->ID_livro = $ID_livro;
    }

    public function getIDLivro()
    {
        return $this->ID_livro;
    }

    public function setIDGestor($ID_gestor)
    {
        $this->ID_gestor = $ID_gestor;
    }

    public function getIDGestor()
    {
        return $this->ID_gestor;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setRegistradoEm($registrado_em)
    {
        $this->registrado_em = $registrado_em;
    }

    public function getRegistradoEm()
    {
        return $this->registrado_em;
    }
}
