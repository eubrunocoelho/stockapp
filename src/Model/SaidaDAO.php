<?php

namespace App\Model;

use PDO;

class SaidaDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function register(Saida $saida)
    {
        $SQL =
            'INSERT INTO
             saida (
                 ID_livro,
                 ID_gestor,
                 quantidade,
                 registrado_em
             )
             VALUES (
                 :ID_livro,
                 :ID_gestor,
                 :quantidade,
                 :registrado_em
             )';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $saida->getIDLivro());
        $stmt->bindValue(':ID_gestor', $saida->getIDGestor());
        $stmt->bindValue(':quantidade', $saida->getQuantidade());
        $stmt->bindValue(':registrado_em', $saida->getRegistradoEm());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function deleteSaidaByIDLivro(Saida $saida)
    {
        $SQL =
            'DELETE FROM
                 saida
             WHERE
                 ID_livro = :ID_livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $saida->getIDLivro());

        return ($stmt->execute()) ? true : false;
    }
}
