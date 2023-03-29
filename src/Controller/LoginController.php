<?php

namespace App\Controller;

use Psr\{
    Http\Message\ResponseInterface as Response,
    Http\Message\ServerRequestInterface as Request
};

use Slim\{
    App,
    Views\PhpRenderer
};

class LoginController
{
    private $app, $container, $renderer;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->renderer = $this->container->get(PhpRenderer::class);
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        return $this->renderer->render($response, 'login/login.php');
    }
}
