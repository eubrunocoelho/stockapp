<?php

namespace App\Controller;

use Psr\{
    Http\Message\ResponseInterface as Response,
    Http\Message\ServerRequestInterface as Request
};

use Slim\{
    App,
    Views\PhpRenderer,
    Routing\RouteContext
};

use App\{
    Helper\Input,
    Helper\Session,
    Validator\Validate
};

use App\{
    Model\Gestor,
    Model\GestorDAO
};

use PDO;

/**
 * Responsável por gerenciar o fluxo entre a camada `Model` e a camada `View`
 */
class LoginController
{
    private
        $app, $container, $database, $renderer, $validate;

    private
        $gestor, $gestorDAO;

    public function __construct(App $app)
    {
        // Injeção de depêndencia
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validate = $this->container->get(Validate::class);

        // Conexão com o banco de dados
        $this->gestor = new Gestor();
        $this->gestorDAO = new GestorDAO($this->database);
    }

    /**
     * Responsavel por gerênciar o sistema de login do usuário
     */
    public function login(Request $request, Response $response, array $args): Response
    {
        $basePath = $this->container->get('settings')['api']['path'];

        if ($request->getMethod() == 'POST') {
            // Obtém as requisições de formulário
            $formRequest = (array)$request->getParsedBody();

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
                $this->validate->setFields(['user', 'password']);

                // Define os valores do formulário
                $this->validate->setData($formRequest);

                // Define as regras do formulário
                $this->validate->setRules($rules);

                // Inicia a validação
                $this->validate->validation();

                // Verifica se todos os dados recebidos da requisição condizem com as regras do formulário
                if ($this->validate->passed()) {
                    $this->gestor->setUsuario($formRequest['user']);
                    $this->gestor->setSenha($formRequest['password']);

                    if (($gestor = $this->gestorDAO->checkGestorByCredentials($this->gestor)) !== []) {
                        $gestor = $gestor[0];

                        Session::create('gestorID', $gestor['ID']);
                        Session::create('authenticated', true);

                        $url = RouteContext::fromRequest($request)
                            ->getRouteParser()
                            ->urlFor('dashboard.index');

                        return $response
                            ->withHeader('Location', $url)
                            ->withStatus(302);
                    } else $errors = (array)'Usuário ou senha inválidos.';
                } else {
                    // Obtem os erros que ocorreram durante a validação do formulário
                    $errors = $this->validate->errors();
                }
            }
        }

        $formRequest = $formRequest ?? [];

        // Obtém valores para persistir no formulário
        $inputValues = Input::getPersistValues($formRequest);

        // Inicia a variável `$errors` com seu valor, se não existir retorna um `array` vazio
        $errors = $errors ?? [];

        // Define as variáveis a serem usadas na `view`
        $templateVariables = [
            'basePath' => $basePath,
            'inputValues' => $inputValues,
            'errors' => $errors
        ];

        // Retorna a view de login
        return $this->renderer->render($response, 'login/login.php', $templateVariables);
    }
}
