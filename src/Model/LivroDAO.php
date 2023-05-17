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

    public function getAll()
    {
        $SQL =
            'SELECT * FROM livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getLivroByID(Livro $livro)
    {
        $SQL =
            'SELECT * FROM livro
             WHERE ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $livro->getID());
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
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
                 criado_em
             ) VALUES (
                 :titulo,
                 :formato,
                 :ano_publicacao,
                 :isbn,
                 :edicao,
                 :idioma,
                 :paginas,
                 :descricao,
                 :criado_em
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
        $stmt->bindValue(':criado_em', $livro->getCriadoEm());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $ID = $this->database->lastInsertId();

            return $ID;
        } else return [];
    }

    public function update(Livro $livro)
    {
        $SQL =
            'UPDATE livro
             SET
                 titulo = :titulo,
                 formato = :formato,
                 ano_publicacao = :ano_publicacao,
                 isbn = :isbn,
                 edicao = :edicao,
                 idioma = :idioma,
                 paginas = :paginas,
                 descricao = :descricao
             WHERE
                 ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $livro->getID());
        $stmt->bindValue(':titulo', $livro->getTitulo());
        $stmt->bindValue(':formato', $livro->getFormato());
        $stmt->bindValue(':ano_publicacao', $livro->getAnoPublicacao());
        $stmt->bindValue(':isbn', $livro->getIsbn());
        $stmt->bindValue(':edicao', $livro->getEdicao());
        $stmt->bindValue(':idioma', $livro->getIdioma());
        $stmt->bindValue(':paginas', $livro->getPaginas());
        $stmt->bindValue(':descricao', $livro->getDescricao());

        return ($stmt->execute()) ? true : false;
    }

    public function updateUnidades(Livro $livro)
    {
        $SQL =
            'UPDATE livro
             SET
                 unidades = :unidades
             WHERE
                 ID = :ID';
        
        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $livro->getID());
        $stmt->bindValue(':unidades', $livro->getUnidades());

        return ($stmt->execute()) ? true : false;
    }
}
