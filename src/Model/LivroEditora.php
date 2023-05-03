<?php

namespace App\Model;

class LivroEditora
{
    private
        $ID_livro, $ID_editora;

    public function setIDLivro($ID_livro)
    {
        $this->ID_livro = $ID_livro;
    }

    public function getIDLivro()
    {
        return $this->ID_livro;
    }

    public function setIDEditora($ID_editora)
    {
        $this->ID_editora = $ID_editora;
    }

    public function getIDEditora()
    {
        return $this->ID_editora;
    }
}
