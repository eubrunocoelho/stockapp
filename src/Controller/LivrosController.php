<?php

namespace App\Controller;

use Psr\{
    Http\Message\ResponseInterface as Response,
    Http\Message\ServerRequestInterface as Request
};

use Slim\{
    App,
    Routing\RouteContext,
    Views\PhpRenderer
};

use App\{
    Helper\Session,
    Validator\Validator
};

use PDO;

class LivrosController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);

        parent::__construct($this->app);
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        $basePath = $this->container->get('settings')['api']['path'];
        $gestor = parent::getGestor();
        $gestor = parent::applyGestorData($gestor);

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor
        ];

        return $this->renderer->render($response, 'dashboard/livros/register.php', $templateVariables);
    }
}
