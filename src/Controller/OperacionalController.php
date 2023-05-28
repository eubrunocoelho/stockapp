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
    Helper\DataFilter,
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

class OperacionalController extends GestorController
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
        $URI['listBy'] = $URI['listBy'] ?? 'entrada';

        $basePath = '/' . $this->container->get('settings')['api']['path'];

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

        $pagination['currentPage'] = $URI['page'] ?? 1;
        $pagination['resultLimit'] = 10;
        $pagination['start'] = ($pagination['resultLimit'] * $pagination['currentPage']) - $pagination['resultLimit'];

        if (isset($URI['listBy'])) {
            if ($URI['listBy'] == 'entrada') {
                $status['listBy']['entrada'] = true;

                $index['title'] = 'Histórico de Entradas';
                $index['table']['thead']['unidades'] = 'Unidades (Entrada)';

                $totalRegisters = $this->entradaDAO->getEntradaRegisters()[0]['total_registros'];
                $historico = $this->entradaDAO->getEntradaWithPagination($pagination);
            } else $status['listBy']['entrada'] = false;

            if ($URI['listBy'] == 'saida') {
                $status['listBy']['saida'] = true;

                $index['title'] = 'Histórico de Saídas';
                $index['table']['thead']['unidades'] = 'Unidades (Saída)';

                $totalRegisters = $this->saidaDAO->getSaidaRegisters()[0]['total_registros'];
                $historico = $this->saidaDAO->getSaidaWithPagination($pagination);
            } else $status['listBy']['saida'] = false;
        }

        $pagination['totalPages'] = ceil($totalRegisters / $pagination['resultLimit']);

        $status['listBy']['entrada'] = $status['listBy']['entrada'] ?? false;
        $status['listBy']['saida'] = $status['listBy']['saida'] ?? false;

        if ($status['listBy']['entrada']) $baseLink['listBy'] = '&listBy=entrada';
        elseif (
            !($status['listBy']['entrada']) &&
            !($status['listBy']['saida'])
        ) $baseLink = null;

        if ($status['listBy']['saida']) $baseLink['listBy'] = '&listBy=saida';
        elseif (
            !($status['listBy']['entrada']) &&
            !($status['listBy']['saida'])
        ) $baseLink = null;

        $baseLink = $baseLink ?? [];

        $listBy['URL']['entrada'] = $basePath . '/historico?listBy=entrada';
        $listBy['URL']['saida'] = $basePath . '/historico?listBy=saida';
        $pagination['URL']['previous'] = $basePath . '/historico?page=' . $pagination['currentPage'] - 1 . $baseLink['listBy'];
        $pagination['URL']['next'] = $basePath . '/historico?page=' . $pagination['currentPage'] + 1 . $baseLink['listBy'];
        $pagination['URL']['current'] = $basePath . '/historico?page=' . $pagination['currentPage'] . $baseLink['listBy'];

        if (!($pagination['currentPage'] == 1)) $pagination['links']['previous'] = true;
        else $pagination['links']['previous'] = false;

        if (
            !($pagination['currentPage'] == $pagination['totalPages']) &&
            ($pagination['totalPages'] != 0)
        ) $pagination['links']['next'] = true;
        else $pagination['links']['next'] = false;

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'index' => $index,
            'historico' => $historico,
            'listBy' => $listBy,
            'pagination' => $pagination,
            'status' => $status
        ];

        return $this->renderer->render($response, 'dashboard/historico/index.php', $templateVariables);
    }

    public function entrada(Request $request, Response $response, array $args): Response
    {
        $ID = $request->getAttribute('ID');

        $basePath = '/' . $this->container->get('settings')['api']['path'];

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

            if (!(empty($formRequest))) {
                $fields = [
                    'unidades'
                ];

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                $formRequest['unidades'] = intval($formRequest['unidades']); // quickfix

                if (strlen($formRequest['unidades']) > 0) {
                    if (!is_int($formRequest['unidades']))
                        $this->validator->addError('O campo "Unidades" está inválido.');

                    if (!($formRequest['unidades'] > 0))
                        $this->validator->addError('O campo "Unidades" deve conter um valor maior do que 0.');

                    if ($formRequest['unidades'] > 2000)
                        $this->validator->addError('O campo "Unidades" deve conter um valor de no máximo de 2000.');
                }

                if ($this->validator->passed()) {
                    $totalUnidades = (DataFilter::isInteger($livro['unidades']) +
                        DataFilter::isInteger($formRequest['unidades'])
                    );

                    $this->livro->setUnidades($totalUnidades);
                    if ($this->livroDAO->updateUnidades($this->livro)) {
                        $dataWrite = [
                            'ID_livro' => $livro['ID'] ?? null,
                            'ID_gestor' => $gestor['ID'] ?? null,
                            'quantidade' => DataFilter::isInteger($formRequest['unidades']) ?? null,
                            'registrado_em' => date('Y-m-d H:i:s') ?? null
                        ];

                        $this->entrada->setIDLivro($dataWrite['ID_livro']);
                        $this->entrada->setIDGestor($dataWrite['ID_gestor']);
                        $this->entrada->setQuantidade($dataWrite['quantidade']);
                        $this->entrada->setRegistradoEm($dataWrite['registrado_em']);

                        if ($this->entradaDAO->register($this->entrada)) {
                            $this->container->get('flash')
                                ->addMessage('message.success', 'Entrada registrada com sucesso!');

                            $url = RouteContext::fromRequest($request)
                                ->getRouteParser()
                                ->urlFor('livros.show', ['ID' => $livro['ID']]);

                            return $response
                                ->withHeader('Location', $url)
                                ->withStatus(302);
                        } else $errors = (array)'Houve um erro inesperado.';
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

        $basePath = '/' . $this->container->get('settings')['api']['path'];

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

                $formRequest['unidades'] = intval($formRequest['unidades']); // quickfix

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
                    $totalUnidades = (DataFilter::isInteger($livro['unidades']) -
                        DataFilter::isInteger($formRequest['unidades'])
                    );

                    $this->livro->setUnidades($totalUnidades);
                    if ($this->livroDAO->updateUnidades($this->livro)) {
                        $dataWrite = [
                            'ID_livro' => $livro['ID'] ?? null,
                            'ID_gestor' => $gestor['ID'] ?? null,
                            'quantidade' => DataFilter::isInteger($formRequest['unidades']) ?? null,
                            'registrado_em' => date('Y-m-d H:i:s') ?? null
                        ];

                        $this->saida->setIDLivro($dataWrite['ID_livro']);
                        $this->saida->setIDGestor($dataWrite['ID_gestor']);
                        $this->saida->setQuantidade($dataWrite['quantidade']);
                        $this->saida->setRegistradoEm($dataWrite['registrado_em']);

                        if ($this->saidaDAO->register($this->saida)) {
                            $this->container->get('flash')
                                ->addMessage('message.success', 'Saída registrada com sucesso!');

                            $url = RouteContext::fromRequest($request)
                                ->getRouteParser()
                                ->urlFor('livros.show', ['ID' => $livro['ID']]);

                            return $response
                                ->withHeader('Location', $url)
                                ->withStatus(302);
                        } else $errors = (array)'Houve um erro inesperado.';
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
