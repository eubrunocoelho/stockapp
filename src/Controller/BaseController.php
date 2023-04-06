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

abstract class BaseController
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
}
