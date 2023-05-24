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
    Model\EntradaDAO,
    Model\Saida,
    Model\SaidaDAO
};

use PDO;

class EntradaSaidaController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    private
        $livro, $livroDAO, $entrada, $entradaDAO, $saida, $saidaDAO;

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
        $this->saida = new Saida();
        $this->saidaDAO = new SaidaDAO($this->database);

        parent::__construct($this->app);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $URI = (array)$request->getQueryParams();

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

        $URI['list'] = $URI['list'] ?? 'entrada';

        $pagination['currentPage'] = $URI['page'] ?? 1;
        $pagination['resultLimit'] = 3;
        $pagination['start'] = ($pagination['resultLimit'] * $pagination['currentPage']) - $pagination['resultLimit'];

        if (isset($URI['list'])) {
            if ($URI['list'] == 'entrada') {
                $status['listBy']['entrada'] = true;

                $list['title'] = 'Histórico de Entrada';
                $list['table']['thead']['unidades'] = 'Unidades (Entrada)';

                $totalRegisters = $this->entradaDAO->getEntradaRegisters()[0]['total_registros'];
                $historico = $this->entradaDAO->getEntradaWithPagination($pagination);
            }
        }

        $pagination['totalPages'] = ceil($totalRegisters / $pagination['resultLimit']);

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'list' => $list,
            'historico' => $historico
        ];

        return $this->renderer->render($response, 'dashboard/historico/index.php', $templateVariables);
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
        if ($this->livroDAO->getLivroByID($this->livro) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('livros.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $livro = $this->livroDAO->getLivroByID($this->livro)[0];

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

            $rules = [
                'unidades' => [
                    'label' => 'Unidades',
                    'required' => true
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

                $formRequest['unidades'] = intval($formRequest['unidades']);

                if (strlen($formRequest['unidades']) > 0) {
                    if (!is_int($formRequest['unidades']))
                        $this->validator->addError('O campo "Unidades" está inválido.');

                    if (!($formRequest['unidades'] > 0))
                        $this->validator->addError('O campo "Unidades" deve conter um valor maior do que 0.');

                    if ($formRequest['unidades'] > 2000)
                        $this->validator->addError('O campo "Unidades" deve conter um valor de no máximo de 2000.');
                }

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

                        if ($this->entradaDAO->register($this->entrada)) dd('OK');
                        else $errors = (array)'Houve um erro inesperado.';
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

    public function saida(Request $request, Response $response, array $args): Response
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
        if ($this->livroDAO->getLivroByID($this->livro) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('livros.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $livro = $this->livroDAO->getLivroByID($this->livro)[0];

        if ($request->getMethod() == 'POST') {
            if ($ID !== Session::get('livro.saida.ID')) {
                $url = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('livros.entrada', ['ID' => Session::get('livro.entrada.ID')]);

                return $response
                    ->withAddedHeader('Location', $url)
                    ->withStatus(302);
            }

            Session::delete('livro.saida.ID');

            $formRequest = (array)$request->getParsedBody();

            $rules = [
                'unidades' => [
                    'label' => 'Unidades',
                    'required' => true
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

                $formRequest['unidades'] = intval($formRequest['unidades']);

                if (strlen($formRequest['unidades']) > 0) {
                    if (!is_int($formRequest['unidades']))
                        $this->validator->addError('O campo "Unidades" está inválido.');

                    if (!($formRequest['unidades']) > 0)
                        $this->validator->addError('O campo "Unidades" deve conter um valor maior do que 0.');

                    if ($formRequest['unidades'] > 2000)
                        $this->validator->addError('O campo "Unidades" deve conter um valor de no máximo de 2000.');

                    if ($formRequest['unidades'] > $livro['unidades'])
                        $this->validator->addError('Não há está quantidade de unidades disponíveis.');
                }

                if ($this->validator->passed()) {
                    $totalUnidades = ($livro['unidades'] - $formRequest['unidades']);

                    $this->livro->setUnidades($totalUnidades);
                    if ($this->livroDAO->updateUnidades($this->livro)) {
                        $dataWrite = [
                            'ID_livro' => $livro['ID'] ?? null,
                            'ID_gestor' => $gestor['ID'] ?? null,
                            'quantidade' => $formRequest['unidades'] ?? null,
                            'registrado_em' => date('Y-m-d H:i:s') ?? null
                        ];

                        $this->saida->setIDLivro($dataWrite['ID_livro']);
                        $this->saida->setIDGestor($dataWrite['ID_gestor']);
                        $this->saida->setQuantidade($dataWrite['quantidade']);
                        $this->saida->setRegistradoEm($dataWrite['registrado_em']);

                        if ($this->saidaDAO->register($this->saida)) dd('OK');
                        else $errors = (array)'Houve um erro inesperado.';
                    } else $errors = (array)'Houve um erro inesperado.';
                } else $errors = array_unique($this->validator->errors());
            }
        }

        Session::create('livro.saida.ID', $ID);

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

        return $this->renderer->render($response, 'dashboard/livros/saida.php', $templateVariables);
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
