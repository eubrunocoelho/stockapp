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
}
