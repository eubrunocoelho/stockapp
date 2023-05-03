<?php

namespace App\Model;

use PDO;

class EditoraDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getEditoraByNome(Editora $editora)
    {
        $SQL =
            'SELECT * FROM editora
             WHERE nome = :nome';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':nome', $editora->getNome());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function register(Editora $editora)
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
        $stmt->bindValue(':nome', $editora->getNome());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $ID = $this->database->lastInsertId();

            return $ID;
        } else return [];
    }
}
