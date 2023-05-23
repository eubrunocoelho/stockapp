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
