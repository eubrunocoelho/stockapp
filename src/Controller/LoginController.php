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
    Helper\Input,
    Validator\Validator
};

/**
 * Responsável por gerenciar o fluxo entre a camada `Model` e a camada `View`
 */
class LoginController
{
    private
        $app, $container, $renderer, $validator;

    public function __construct(App $app)
    {
        // Define os atributos de depêndencia
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);
    }

    /**
     * Responsavel por gerênciar o sistema de login do usuário
     */
    public function login(Request $request, Response $response, array $args): Response
    {
        // Obtém as requisições de formulário
        $formRequest = (array)$request->getParsedBody();

        // Obtém valores para persistir no formulário
        $inputValues = Input::getPersistValues($formRequest);

        $rules = [
            'user' => [
                'label' => 'Usuário',
                'required' => true
            ],
            'password' => [
                'label' => 'Senha',
                'required' => true
            ]
        ];

        if (!empty($formRequest)) {
            $this->validator->setFields(['user', 'password']);
            $this->validator->setData($formRequest);
            $this->validator->setRules($rules);
            $this->validator->validation();

            if (!$this->validator->passed()) $errors = (array)$this->validator->errors();
        }

        $errors = $errors ?? '';

        // Define as variáveis da `view`
        $templateVariables = [
            'inputValues' => $inputValues,
            'errors' => $errors
        ];

        // Retorna a view de login
        return $this->renderer->render($response, 'login/login.php', $templateVariables);
    }
}
