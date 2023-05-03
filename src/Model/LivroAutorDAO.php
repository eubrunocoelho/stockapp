<?php

namespace App\Model;

use PDO;

class LivroAutorDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function register(LivroAutor $livroAutor)
    {
        $SQL = 
            'INSERT INTO
             livro_autor (
                ID_livro,
                ID_autor
             ) VALUES (
                :ID_livro,
                :ID_autor
             )';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroAutor->getIDLivro());
        $stmt->bindValue(':ID_autor', $livroAutor->getIDAutor());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }
}
