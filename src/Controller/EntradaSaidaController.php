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

use App\{
    Model\Livro,
    Model\LivroDAO
};

use PDO;

class EntradaSaidaController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    private
        $livro, $livroDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);

        $this->livro = new Livro();
        $this->livroDAO = new LivroDAO($this->database);

        parent::__construct($this->app);
    }

    public function entrada(Request $request, Response $response, array $args): Response
    {
        $ID = $request->getAttribute('ID');

        $basePath = $this->container->get('settings')['api']['path'];

        $gestor = parent::getGestor();
        $gestor = parent::applyGestorData($gestor);

        if ($gestor === []) {
            Session::destroy();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $this->livro->setID($ID);
        $livro = $this->livroDAO->getLivroByID($this->livro)[0];

        // ID da URL
        $this->livro->setID($ID);
        if ($this->livroDAO->getLivroByID($this->livro) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('livros.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        if ($request->getMethod() == 'POST') {
            $formRequest = (array)$request->getParsedBody();

            $regex = [
                'unidades' => '/^([0-9]|[1-9][0-9]{1,3}|[1-4][0-9]{4}|20000)$/'
            ];

            $rules = [
                'unidades' => [
                    'label' => 'Unidades',
                    'required' => true,
                    'regex' => $regex['unidades']
                ]
            ];

            if (!empty($formRequest)) {
                $fields = [
                    'unidades'
                ];

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                if ($this->validator->passed()) {
                } else $errors = array_unique($this->validator->errors());
            }
        }

        $errors = $errors ?? [];

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'livro' => $livro,
            'errors' => $errors
        ];

        return $this->renderer->render($response, 'dashboard/livros/entrada.php', $templateVariables);
    }
}
