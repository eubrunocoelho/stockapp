<?php

namespace App\Model;

use PDO;

class LivroEditoraDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getEditoraByIDLivro(LivroEditora $livroEditora)
    {
        $SQL =
            'SELECT editora.ID, editora.nome
             FROM editora
             INNER JOIN livro_editora ON editora.ID = livro_editora.ID_editora
             INNER JOIN livro ON livro.ID = livro_editora.ID_livro
             WHERE ID_livro = :ID_livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroEditora->getIDLivro());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function register(LivroEditora $livroEditora)
    {
        $SQL =
            'INSERT INTO
             livro_editora (
                 ID_livro,
                 ID_editora
             )
             VALUES (
                 :ID_livro,
                 :ID_editora
             )';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroEditora->getIDLivro());
        $stmt->bindValue(':ID_editora', $livroEditora->getIDEditora());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function getLivroEditoraByIDLivro(LivroEditora $livroEditora)
    {
        $SQL =
            'SELECT * FROM
                 livro_editora
             WHERE
                 ID_livro = :ID_livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroEditora->getIDLivro());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getLivroEditoraByOtherIDLivroAndByIDEditora(LivroEditora $livroEditora)
    {
        $SQL =
            'SELECT * FROM
                 livro_editora
             WHERE
                 ID_livro != :ID_livro AND
                 ID_editora = :ID_editora';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroEditora->getIDLivro());
        $stmt->bindValue(':ID_editora', $livroEditora->getIDEditora());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function deleteLivroEditoraByIDLivro(LivroEditora $livroEditora)
    {
        $SQL =
            'DELETE FROM
                 livro_editora
             WHERE
                 ID_livro = :ID_livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroEditora->getIDLivro());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }
}
