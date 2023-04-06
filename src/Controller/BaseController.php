<?php

namespace App\Controller;

use Psr\{
    Http\Message\ResponseInterface as Response,
    Http\Message\ServerRequestInterface as Request
};

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
use Slim\Routing\RouteContext;

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

    protected function getGestor(Request $request, Response $response, array $args): Response
    {
        if (Session::exists('gestorID')) $ID = Session::get('gestorID');
        else {
            Session::destroy();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $this->gestor->setID($ID);
        $gestor = $this->gestorDAO->getGestorByID($this->gestor)[0];
    }
}
