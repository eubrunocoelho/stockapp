<?php

namespace App\Model;

class LivroAutor
{
    private
        $ID_livro, $ID_autor;

    public function setIDLivro($ID_livro)
    {
        $this->ID_livro = $ID_livro;
    }

    public function getIDLivro()
    {
        return $this->ID_livro;
    }

    public function setIDAutor($ID_autor)
    {
        $this->ID_autor = $ID_autor;
    }

    public function getIDAutor()
    {
        return $this->ID_autor;
    }
}
