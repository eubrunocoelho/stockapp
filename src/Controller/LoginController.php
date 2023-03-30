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

use App\{
    Helper\Input
};

class LoginController
{
    private
        $app, $container, $renderer;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->renderer = $this->container->get(PhpRenderer::class);
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        // Obtém requisição de formulário
        $formRequest = (array)$request->getParsedBody();

        // Obtém valores para persistir no formulário
        $inputValues = Input::getPersistValues($formRequest);

        $templateVariables = [
            'inputValues' => $inputValues
        ];

        return $this->renderer->render($response, 'login/login.php', $templateVariables);
    }
}
