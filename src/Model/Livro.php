<?php

namespace App\Model;

class Livro
{
    private
        $ID, $titulo, $formato, $ano_publicacao, $isbn, $edicao, $idioma, $paginas, $descricao, $unidades;

    public function setID($ID)
    {
        $this->ID = $ID;
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setTitutlo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setFormato($formato)
    {
        $this->formato = $formato;
    }

    public function getFormato()
    {
        return $this->formato;
    }

    public function setAnoPublicacao($ano_publicacao)
    {
        $this->ano_publicacao = $ano_publicacao;
    }

    public function getAnoPublicacao()
    {
        return $this->ano_publicacao;
    }

    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function setEdicao($edicao)
    {
        $this->edicao = $edicao;
    }

    public function getEdicao()
    {
        return $this->edicao;
    }

    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
    }

    public function getIdioma()
    {
        return $this->idioma;
    }

    public function setPaginas($paginas)
    {
        $this->paginas = $paginas;
    }

    public function getPaginas()
    {
        return $this->paginas;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;
    }

    public function getUnidades()
    {
        return $this->unidades;
    }
}
