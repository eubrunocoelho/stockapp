<?php

namespace App\Model;

use PDO;

class LivroDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getLivroByID(Livro $livro)
    {
        $SQL =
            'SELECT * FROM livro
             WHERE ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $livro->getID());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getRecentes()
    {
        $SQL =
            'SELECT * FROM livro
             ORDER BY ID DESC
             LIMIT 1, 10';
        
        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getAllWithPagination($pagination)
    {
        $SQL =
            'SELECT * FROM livro
             ORDER BY ID DESC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getSearchWithPagination($pagination, $search)
    {
        $SQL =
            'SELECT * FROM livro
             WHERE titulo LIKE :titulo
             ORDER BY ID DESC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $search['data']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getOrderByAntigosWithPagination($pagination)
    {
        $SQL =
            'SELECT * FROM livro
             ORDER BY ID ASC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getOrderByUnitsWithPagination($pagination)
    {
        $SQL =
            'SELECT * FROM livro
             ORDER BY unidades DESC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getOrderByAToZ($pagination)
    {
        $SQL =
            'SELECT * FROM livro
             ORDER BY titulo ASC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getOrderByZToA($pagination)
    {
        $SQL =
            'SELECT * FROM livro
             ORDER BY titulo DESC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchALl(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getSearchAndOrderByAntigosWithPagination($pagination, $search)
    {
        $SQL =
            'SELECT * FROM livro
             WHERE titulo LIKE :titulo
             ORDER BY ID ASC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $search['data']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getSearchAndOrderByUnitsWithPagination($pagination, $search)
    {
        $SQL =
            'SELECT * FROM livro
             WHERE titulo LIKE :titulo
             ORDER BY unidades DESC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $search['data']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getSearchAndOrderByAToZWithPagination($pagination, $search)
    {
        $SQL =
            'SELECT * FROM livro
             WHERE titulo LIKE :titulo
             ORDER BY titulo ASC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $search['data']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getSearchAndOrderByZToAWithPagination($pagination, $search)
    {
        $SQL =
            'SELECT * FROM livro
             WHERE titulo LIKE :titulo
             ORDER BY titulo DESC
             LIMIT ' . $pagination['start'] . ', ' . $pagination['resultLimit'];

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $search['data']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getTotalRegisters()
    {
        $SQL =
            'SELECT COUNT(ID) AS total_registros FROM livro';

        $stmt = $this->database->prepare($SQL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function getSearchRegisters($search)
    {
        $SQL =
            'SELECT COUNT(ID) AS total_registros FROM livro
             WHERE titulo LIKE :titulo';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $search['data']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else return [];
    }

    public function register(Livro $livro)
    {
        $SQL =
            'INSERT INTO
             livro (
                 titulo,
                 formato,
                 ano_publicacao,
                 isbn,
                 edicao,
                 idioma,
                 paginas,
                 descricao,
                 registrado_em
             ) VALUES (
                 :titulo,
                 :formato,
                 :ano_publicacao,
                 :isbn,
                 :edicao,
                 :idioma,
                 :paginas,
                 :descricao,
                 :registrado_em
             )';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':titulo', $livro->getTitulo());
        $stmt->bindValue(':formato', $livro->getFormato());
        $stmt->bindValue(':ano_publicacao', $livro->getAnoPublicacao());
        $stmt->bindValue(':isbn', $livro->getIsbn());
        $stmt->bindValue(':edicao', $livro->getEdicao());
        $stmt->bindValue(':idioma', $livro->getIdioma());
        $stmt->bindValue(':paginas', $livro->getPaginas());
        $stmt->bindValue(':descricao', $livro->getDescricao());
        $stmt->bindValue(':registrado_em', $livro->getRegistradoEm());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $ID = $this->database->lastInsertId();

            return $ID;
        } else return [];
    }

    public function update(Livro $livro)
    {
        $SQL =
            'UPDATE livro
             SET
                 titulo = :titulo,
                 formato = :formato,
                 ano_publicacao = :ano_publicacao,
                 isbn = :isbn,
                 edicao = :edicao,
                 idioma = :idioma,
                 paginas = :paginas,
                 descricao = :descricao
             WHERE
                 ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $livro->getID());
        $stmt->bindValue(':titulo', $livro->getTitulo());
        $stmt->bindValue(':formato', $livro->getFormato());
        $stmt->bindValue(':ano_publicacao', $livro->getAnoPublicacao());
        $stmt->bindValue(':isbn', $livro->getIsbn());
        $stmt->bindValue(':edicao', $livro->getEdicao());
        $stmt->bindValue(':idioma', $livro->getIdioma());
        $stmt->bindValue(':paginas', $livro->getPaginas());
        $stmt->bindValue(':descricao', $livro->getDescricao());

        return ($stmt->execute()) ? true : false;
    }

    public function updateUnidades(Livro $livro)
    {
        $SQL =
            'UPDATE livro
             SET
                 unidades = :unidades
             WHERE
                 ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $livro->getID());
        $stmt->bindValue(':unidades', $livro->getUnidades());

        return ($stmt->execute()) ? true : false;
    }

    public function deleteLivroByID(Livro $livro)
    {
        $SQL =
            'DELETE FROM 
                 livro
             WHERE
                 ID = :ID';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':ID', $livro->getID());

        return ($stmt->execute()) ? true : false;
    }
}
