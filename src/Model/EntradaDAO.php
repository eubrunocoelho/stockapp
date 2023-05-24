<?php

namespace App\Model;

use PDO;

class EntradaDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getEntradaWithPagination($pagination)
    {
        $SQL =
            'SELECT entrada.ID, entrada.ID_livro, livro.titulo, entrada.quantidade, entrada.registrado_em
             FROM entrada
             INNER JOIN livro ON entrada.ID_livro = livro.ID
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getEntradaRegisters()
    {
        $SQL =
            'SELECT COUNT(ID) AS total_registros FROM entrada';

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function register(Entrada $entrada)
    {
        $SQL =
            'INSERT INTO
             entrada (
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
        $stmt->bindValue(':ID_livro', $entrada->getIDLivro());
        $stmt->bindValue(':ID_gestor', $entrada->getIDGestor());
        $stmt->bindValue(':quantidade', $entrada->getQuantidade());
        $stmt->bindValue(':registrado_em', $entrada->getRegistradoEm());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function deleteEntradaByIDLivro(Entrada $entrada)
    {
        $SQL =
            'DELETE FROM
                 entrada
             WHERE
                 ID_livro = :ID_livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID_livro', $entrada->getIDLivro());

        return ($stmt->execute()) ? true : false;
    }
}
