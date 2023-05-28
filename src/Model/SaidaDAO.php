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

    public function getSaidaWithPagination($pagination)
    {
        $SQL =
            'SELECT saida.ID, saida.ID_livro, livro.titulo, saida.quantidade, saida.registrado_em
             FROM saida
             INNER JOIN livro ON saida.ID_livro = livro.ID
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getSaidaRegisters()
    {
        $SQL =
            'SELECT COUNT(ID) AS total_registros FROM saida';

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getTotalSaidaQuantidade()
    {
        $SQL =
            'SELECT SUM(quantidade) AS total_quantidade FROM saida';

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
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
