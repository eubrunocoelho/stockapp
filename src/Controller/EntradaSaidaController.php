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
    Model\LivroDAO,
    Model\Entrada,
    Model\EntradaDAO
};

use PDO;

class EntradaSaidaController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    private
        $livro, $livroDAO, $entrada, $entradaDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);

        $this->livro = new Livro();
        $this->livroDAO = new LivroDAO($this->database);
        $this->entrada = new Entrada();
        $this->entradaDAO = new EntradaDAO($this->database);

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
            if ($ID !== Session::get('livro.entrada.ID')) {
                $url = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('livros.entrada', ['ID' => Session::get('livro.entrada.ID')]);

                return $response
                    ->withAddedHeader('Location', $url)
                    ->withStatus(302);
            }

            Session::delete('livro.entrada.ID');

            $formRequest = (array)$request->getParsedBody();

            $regex = [
                'unidades' => '/^([1-9]|[1-9][0-9]{1,2}|1[0-9]{3}|2000)$/'
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
                    $totalUnidades = ($livro['unidades'] + $formRequest['unidades']);

                    $this->livro->setUnidades($totalUnidades);
                    if ($this->livroDAO->updateUnidades($this->livro)) {
                        $dataWrite = [
                            'ID_livro' => $livro['ID'] ?? null,
                            'ID_gestor' => $gestor['ID'] ?? null,
                            'quantidade' => $formRequest['unidades'] ?? null,
                            'registrado_em' => date('Y-m-d H:i:s') ?? null
                        ];

                        $this->entrada->setIDLivro($dataWrite['ID_livro']);
                        $this->entrada->setIDGestor($dataWrite['ID_gestor']);
                        $this->entrada->setQuantidade($dataWrite['quantidade']);
                        $this->entrada->setRegistradoEm($dataWrite['registrado_em']);

                        if ($this->entradaDAO->register($this->entrada)) {
                            dd('OK');
                        }
                    } else $errors = (array)'Houve um erro inesperado.';
                } else $errors = array_unique($this->validator->errors());
            }
        }

        Session::create('livro.entrada.ID', $ID);

        $formRequest = $formRequest ?? [];
        $persistRegisterValues = self::getPersistRegisterValues($formRequest);
        $errors = $errors ?? [];

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'livro' => $livro,
            'persistRegisterValues' => $persistRegisterValues,
            'errors' => $errors
        ];

        return $this->renderer->render($response, 'dashboard/livros/entrada.php', $templateVariables);
    }

    private static function getPersistRegisterValues($request)
    {
        foreach ($request as $key => $value) {
            if ($value !== '') $request[$key] = $value;
            else $request[$key] = null;
        }

        return $request;
    }
}
