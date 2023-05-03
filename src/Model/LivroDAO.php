<?php

namespace App\Model;

use PDO;

class LivroDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function register(Livro $livro)
    {
        $SQL =
            'INSERT INTO
             livro (
                 titulo,
                 formato,
                 ano_publicacao,
                 isbn,
                 edicao,
                 idioma,
                 paginas,
                 descricao,
                 unidades
             ) VALUES (
                 :titulo,
                 :formato,
                 :ano_publicacao,
                 :isbn,
                 :edicao,
                 :idioma,
                 :paginas,
                 :descricao,
                 :unidades
             )';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $livro->getTitulo());
        $stmt->bindValue(':formato', $livro->getFormato());
        $stmt->bindValue(':ano_publicacao', $livro->getAnoPublicacao());
        $stmt->bindValue(':isbn', $livro->getIsbn());
        $stmt->bindValue(':edicao', $livro->getEdicao());
        $stmt->bindValue(':idioma', $livro->getIdioma());
        $stmt->bindValue(':paginas', $livro->getPaginas());
        $stmt->bindValue(':descricao', $livro->getDescricao());
        $stmt->bindValue(':unidades', $livro->getUnidades());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $ID = $this->database->lastInsertId();

            return $ID;
        } else return [];
    }
}
