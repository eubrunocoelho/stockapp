<?php

namespace App\Model;

use PDO;

class GestorDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function checkGestorByCredentials(Gestor $gestor)
    {
        $SQL =
            'SELECT * FROM gestor 
             WHERE (email = :usuario OR cpf = :usuario) 
             AND senha = :senha';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':usuario', $gestor->getUsuario());
        $stmt->bindValue(':senha', $gestor->getSenha());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getGestorByID(Gestor $gestor)
    {
        $SQL =
            'SELECT * FROM gestor
             WHERE ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $gestor->getID());
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }
}
