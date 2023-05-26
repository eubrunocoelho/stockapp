<?php

namespace App\Middleware;

use Psr\{
    Http\Message\ServerRequestInterface as Request,
    Http\Server\RequestHandlerInterface as RequestHandler
};

use Slim\{
    App,
    Psr7\Response,
    Routing\RouteContext
};

use App\{
    Helper\Session
};

use App\{
    Model\Gestor,
    Model\GestorDAO
};

use PDO;

class Unauthenticated
{
    private
        $app, $container, $database;

    private
        $gestor, $gestorDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);

        $this->gestor = new Gestor();
        $this->gestorDAO = new GestorDAO($this->database);
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (
            (!Session::exists('gestorID')) &&
            (!Session::exists('authenticated'))
        ) {
            Session::destroy();
            
            $response = new Response();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }
        
        $this->gestor->setID(Session::get('gestorID'));
        $gestor = $this->gestorDAO->getGestorByID($this->gestor);
        
        if ($gestor === []) {
            Session::destroy();

            $response = new Response();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $response = $handler->handle($request);
        return $response;
    }
}
