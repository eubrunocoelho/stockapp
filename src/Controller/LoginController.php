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
    Helper\Session,
    Validator\Validator
};

use App\{
    Model\Gestor,
    Model\GestorDAO
};

use PDO;

class LoginController
{
    private
        $app, $container, $database, $renderer, $validator;

    private
        $gestor, $gestorDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);

        $this->gestor = new Gestor();
        $this->gestorDAO = new GestorDAO($this->database);
    }

    // OK
    public function login(Request $request, Response $response, array $args): Response
    {
        $basePath = '/' . $this->container->get('settings')['api']['path'];

        if ($request->getMethod() == 'POST') {
            $formRequest = (array)$request->getParsedBody();

            $rules = [
                'email' => [
                    'label' => 'E-mail',
                    'required' => true
                ],
                'senha' => [
                    'label' => 'Senha',
                    'required' => true
                ]
            ];

            if (!(empty($formRequest))) {
                $this->validator->setFields([
                    'email',
                    'senha'
                ]);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                if ($this->validator->passed()) {
                    $this->gestor->setEmail($formRequest['email']);
                    $this->gestor->setSenha($formRequest['senha']);

                    if (
                        ($gestor = $this->gestorDAO->checkGestorByCredentials($this->gestor)) !== []
                    ) {
                        Session::create('gestorID', $gestor[0]['ID']);
                        Session::create('authenticated', true);

                        $url = RouteContext::fromRequest($request)
                            ->getRouteParser()
                            ->urlFor('dashboard.index');

                        return $response
                            ->withHeader('Location', $url)
                            ->withStatus(302);
                    } else $errors = (array)'Usuário ou senha inválidos.';
                } else $errors = array_unique($this->validator->errors());
            }
        }

        $formRequest = $formRequest ?? [];
        $persistLoginValues = self::getPersistLoginValues($formRequest);
        $errors = $errors ?? [];

        $templateVariables = [
            'basePath' => $basePath,
            'persistLoginValues' => $persistLoginValues,
            'errors' => $errors
        ];

        return $this->renderer->render($response, 'login/login.php', $templateVariables);
    }

    private static function getPersistLoginValues($request)
    {
        foreach ($request as $key => $value) {
            if ($value !== '') $request[$key] = $value;
            else $request[$key] = null;
        }

        return $request;
    }
}
