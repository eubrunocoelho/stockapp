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

        // Aplica regras das requisições do formulário
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

        // Verifica se existe requisições do formulário
        if (!empty($formRequest)) {
            // Define campos que devem existir no formulário
            $this->validator->setFields(['user', 'password']);

            // Define os valores do formulário
            $this->validator->setData($formRequest);

            // Define as regras do formulário
            $this->validator->setRules($rules);

            // Inicia a validação
            $this->validator->validation();

            // Verifica se todos os dados recebidos da requisição condizem com as regras do formulário
            if (!$this->validator->passed())
                // Obtem os erros que ocorreram durante a validação do formulário
                $errors = (array)$this->validator->errors();
        }

        // Inicia a variável `$errors` com seu valor, se não existir retorna um `array` vazio
        $errors = $errors ?? [];

        // Define as variáveis a serem usadas na `view`
        $templateVariables = [
            'inputValues' => $inputValues,
            'errors' => $errors
        ];

        // Retorna a view de login
        return $this->renderer->render($response, 'login/login.php', $templateVariables);
    }
}
