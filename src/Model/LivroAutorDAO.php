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
             ) 
             VALUES (
                 :ID_livro,
                 :ID_autor
             )';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroAutor->getIDLivro());
        $stmt->bindValue(':ID_autor', $livroAutor->getIDAutor());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function getLivroAutorByIDLivro(LivroAutor $livroAutor)
    {
        $SQL =
            'SELECT * FROM
                 livro_autor
             WHERE
                 ID_livro = :ID_livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroAutor->getIDLivro());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getLivroAutorByOtherIDLivroAndByIDAutor(LivroAutor $livroAutor)
    {
        $SQL = 
            'SELECT * FROM
                 livro_autor
             WHERE
                 ID_livro != :ID_livro AND
                 ID_autor = :ID_autor';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroAutor->getIDLivro());
        $stmt->bindValue(':ID_autor', $livroAutor->getIDAutor());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function deleteLivroAutorByIDLivro(LivroAutor $livroAutor)
    {
        $SQL =
            'DELETE FROM
                 livro_autor
             WHERE
                 ID_livro = :ID_livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $livroAutor->getIDLivro());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }
}
