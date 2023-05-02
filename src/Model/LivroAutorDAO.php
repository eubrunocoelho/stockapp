<?php

namespace App\Model;

use PDO;

class LivroAutorDAO
{
    private
        $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    
}
