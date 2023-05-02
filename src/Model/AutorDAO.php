<?php

namespace App\Model;

use PDO;

class AutorDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function checkAutorByNome(Autor $autor)
    {
        $SQL =
            'SELECT * FROM autor
             WHERE nome = :nome';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':nome', $autor->getNome());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function register(Autor $autor)
    {
        $SQL =
            'INSERT INTO
             autor (
                nome
             )
             VALUES (
                :nome
             )';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':nome', $autor->getNome());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }
}
