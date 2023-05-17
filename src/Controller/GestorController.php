<?php

namespace App\Controller;

use Slim\{
    App
};

use App\{
    Helper\Session
};

use App\{
    Model\Gestor,
    Model\GestorDAO
};

use PDO;

abstract class GestorController
{
    private
        $app, $container, $database;

    private
        $gestor, $gestorDAO;

    protected function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);

        $this->gestor = new Gestor();
        $this->gestorDAO = new GestorDAO($this->database);
    }

    protected function getGestor()
    {
        if (Session::exists('gestorID')) $ID = Session::get('gestorID');
        else $ID = null;

        $this->gestor->setID($ID);
        $gestor = $this->gestorDAO->getGestorByID($this->gestor)[0] ?? [];

        return $gestor;
    }

    protected function applyGestorData($gestor)
    {
        $gestor['cargo'] = self::getCargo($gestor['cargo']);
        $gestor['genero'] = self::getGenero($gestor['genero']);
        $gestor['status'] = self::getStatus($gestor['status']);

        return $gestor;
    }

    private static function getCargo($cargo)
    {
        switch ($cargo) {
            case 1:
                $cargo = 'Administrador';
                break;
            case 2:
                $cargo = 'Gestor';
                break;
            default:
                $cargo = 'Indefinido';
        }

        return $cargo;
    }

    private static function getGenero($genero)
    {
        switch ($genero) {
            case 1:
                $genero = 'Masculino';
                break;
            case 2:
                $genero = 'Feminino';
                break;
            default:
                $genero = 'Indefinido';
        }

        return $genero;
    }

    private static function getStatus($status)
    {
        switch ($status) {
            case 1:
                $status = 'Ativo';
                break;
            case 2:
                $status = 'Inativo';
                break;
            default:
                $status = 'Indefinido';
        }

        return $status;
    }
}
