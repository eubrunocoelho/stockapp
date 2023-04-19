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

    public function update(Gestor $gestor)
    {
        $SQL = 
            'UPDATE gestor
             SET 
                 nome = :nome,
                 email = :email,
                 cpf = :cpf,
                 telefone = :telefone,
                 endereco = :endereco,
                 cargo = :cargo,
                 genero = :genero,
                 status = :status,
                 img_url = :img_url
             WHERE
                 ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $gestor->getID());
        $stmt->bindValue(':nome', $gestor->getNome());
        $stmt->bindValue(':email', $gestor->getEmail());
        $stmt->bindValue(':cpf', $gestor->getCpf());
        $stmt->bindValue(':telefone', $gestor->getTelefone());
        $stmt->bindValue(':endereco', $gestor->getEndereco());
        $stmt->bindValue(':cargo', $gestor->getCargo());
        $stmt->bindValue(':genero', $gestor->getGenero());
        $stmt->bindValue(':status', $gestor->getStatus());
        $stmt->bindValue(':img_url', $gestor->getImgUrl());

        return ($stmt->execute()) ? true : false;
    }
}
