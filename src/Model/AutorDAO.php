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

    public function getAutorByNome(Autor $autor)
    {
        $SQL =
            'SELECT * FROM autor
             WHERE nome = :nome';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':nome', $autor->getNome());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
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

        if ($stmt->rowCount() > 0) {
            $ID = $this->database->lastInsertId();

            return $ID;
        } else return [];
    }

    public function deleteAutorByID(Autor $autor)
    {
        $SQL =
            'DELETE FROM
                 autor
             WHERE
                 ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $autor->getID());

        return ($stmt->execute()) ? true : false;
    }
}
