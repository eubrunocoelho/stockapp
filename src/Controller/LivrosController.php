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
    Model\Autor,
    Model\AutorDAO,
    Model\Editora,
    Model\EditoraDAO,
    Model\LivroAutor,
    Model\LivroAutorDAO,
    Model\LivroEditora,
    Model\LivroEditoraDAO
};

use PDO;

class LivrosController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    private
        $livro, $livroDAO, $autor, $autorDAO, $editora, $editoraDAO, $livroAutor, $livroAutorDAO, $livroEditora, $livroEditoraDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);

        $this->livro = new Livro();
        $this->livroDAO = new LivroDAO($this->database);
        $this->autor = new Autor();
        $this->autorDAO = new AutorDAO($this->database);
        $this->editora = new Editora();
        $this->editoraDAO = new EditoraDAO($this->database);
        $this->livroAutor = new LivroAutor();
        $this->livroAutorDAO = new LivroAutorDAO($this->database);
        $this->livroEditora = new LivroEditora();
        $this->livroEditoraDAO = new LivroEditoraDAO($this->database);

        parent::__construct($this->app);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $URI = (array)$request->getQueryParams();

        $basePath = $this->container->get('settings')['api']['path'];

        $flash = $this->container->get('flash');
        $messages = $flash->getMessages();

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
        $pagination['resultLimit'] = 3;
        $pagination['start'] = ($pagination['resultLimit'] * $pagination['currentPage']) - $pagination['resultLimit'];

        $totalRegisters = $this->livroDAO->getTotalRegisters()[0]['total_registros'];
        $livros = $this->livroDAO->getAllWithPagination($pagination);

        if (isset($URI['orderBy'])) {
            if ($URI['orderBy'] == 'units') {
                $status['orderBy']['units'] = true;

                $totalRegisters = $this->livroDAO->getTotalRegisters()[0]['total_registros'];
                $livros = $this->livroDAO->getOrderByUnitsWithPagination($pagination);
            } else $status['orderBy']['units'] = false;

            if ($URI['orderBy'] == 'aToZ') {
                $status['orderBy']['aToZ'] = true;

                $totalRegisters = $this->livroDAO->getTotalRegisters()[0]['total_registros'];
                $livros = $this->livroDAO->getOrderByAToZ($pagination);
            } else $status['orderBy']['aToZ'] = false;

            if ($URI['orderBy'] == 'zToA') {
                $status['orderBy']['zToA'] = true;

                $totalRegisters = $this->livroDAO->getTotalRegisters()[0]['total_registros'];
                $livros = $this->livroDAO->getOrderByZToA($pagination);
            } else $status['orderBy']['zToA'] = false;
        }

        if (isset($URI['search'])) {
            $status['search'] = true;
            $search['data'] = '%' . $URI['search'] . '%';

            $totalRegisters = $this->livroDAO->getSearchRegisters($search)[0]['total_registros'];
            $livros = $this->livroDAO->getSearchWithPagination($pagination, $search);
        } else $status['search'] = false;

        if (
            (isset($URI['orderBy'])) &&
            (isset($URI['search']))
        ) {
            $search['data'] = '%' . $URI['search'] . '%';

            if ($URI['orderBy'] == 'units') {
                $status['orderBy']['units'] = true;
                $status['search'] = true;

                $totalRegisters = $this->livroDAO->getSearchRegisters($search)[0]['total_registros'];
                $livros = $this->livroDAO->getSearchAndOrderByUnitsWithPagination($pagination, $search);
            } else $status['orderBy']['units'] = false;

            if ($URI['orderBy'] == 'aToZ') {
                $status['orderBy']['aToZ'] = true;
                $status['search'] = true;

                $totalRegisters = $this->livroDAO->getSearchRegisters($search)[0]['total_registros'];
                $livros = $this->livroDAO->getSearchAndOrderByAToZWithPagination($pagination, $search);
            } else $status['orderBy']['aToZ'] = false;

            if ($URI['orderBy'] == 'zToA') {
                $status['orderBy']['zToA'] = true;
                $status['search'] = true;

                $totalRegisters = $this->livroDAO->getSearchRegisters($search)[0]['total_registros'];
                $livros = $this->livroDAO->getSearchAndOrderByZToAWithPagination($pagination, $search);
            } else $status['orderBy']['zToA'] = false;
        }

        $pagination['totalPages'] = ceil($totalRegisters / $pagination['resultLimit']);

        // ...

        // ...

        $pagination['URL']['previous'] = $basePath . '/livros?page=' . $pagination['currentPage'] - 1;
        $pagination['URL']['next'] = $basePath . '/livros?page=' . $pagination['currentPage'] + 1;
        $pagination['URL']['current'] = $basePath . '/livros?page=' . $pagination['currentPage'];

        if (!($pagination['currentPage'] == 1)) $pagination['links']['previous'] = true;
        else $pagination['links']['previous'] = false;

        if (!($pagination['currentPage'] == $pagination['totalPages'])) $pagination['links']['next'] = true;
        else $pagination['links']['next'] = false;

        foreach ($livros as $key => $value) {
            $this->livroAutor->setIDLivro($livros[$key]['ID']);
            $autor['items'] = $this->livroAutorDAO->getAutorByIDLivro($this->livroAutor);
            $livros[$key]['autor'] = self::autoresOrEditorasToString($autor['items']);

            $this->livroEditora->setIDLivro($livros[$key]['ID']);
            $editora['items'] = $this->livroEditoraDAO->getEditoraByIDLivro($this->livroEditora);
            $livros[$key]['editora'] = self::autoresOrEditorasToString($editora['items']);
        }

        $templateVariables = [
            'basePath' => $basePath,
            'messages' => $messages,
            'gestor' => $gestor,
            'livros' => $livros,
            'pagination' => $pagination
        ];

        return $this->renderer->render($response, 'dashboard/livros/index.php', $templateVariables);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $ID = $request->getAttribute('ID');

        $basePath = $this->container->get('settings')['api']['path'];

        $flash = $this->container->get('flash');
        $messages = $flash->getMessages();

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

        $this->livroAutor->setIDLivro($ID);
        $autor['items'] = $this->livroAutorDAO->getAutorByIDLivro($this->livroAutor);
        $livro['autor'] = self::autoresOrEditorasToString($autor['items']);

        $this->livroEditora->setIDLivro($ID);
        $editora['items'] = $this->livroEditoraDAO->getEditoraByIDLivro($this->livroEditora);
        $livro['editora'] = self::autoresOrEditorasToString($editora['items']);

        if ($this->livroDAO->getLivroByID($this->livro) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('livros.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $templateVariables = [
            'basePath' => $basePath,
            'messages' => $messages,
            'gestor' => $gestor,
            'livro' => $livro
        ];

        return $this->renderer->render($response, 'dashboard/livros/show.php', $templateVariables);
    }

    public function register(Request $request, Response $response, array $args): Response
    {
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

        if ($request->getMethod() == 'POST') {
            $formRequest = (array)$request->getParsedBody();

            $regex = [
                'autor' => '/^[A-Za-z .\'][^0-9,=]+$/',
                'editora' => '/^[A-Za-z0-9 .\'][^,=]+$/',
                'ano_publicacao' => '/^19[0-9][0-9]|20[01][0-9]|202[0-3]$/',
                'edicao' => '/^([1-9]|[0-9][0-9])$/',
                'idioma' => '/^[A-Za-z \'][^0-9.,]+$/',
                'paginas' => '/^([1-9]|[1-9][0-9]{1,4}|1[0-9]{5}|200000)$/'
            ];

            $rules = [
                'titulo' => [
                    'label' => 'Título',
                    'required' => true,
                    'min' => 3,
                    'max' => 255
                ],
                'formato' => [
                    'label' => 'Formato',
                    'required' => false,
                    'min' => 3,
                    'max' => 128
                ],
                'ano_publicacao' => [
                    'label' => 'Ano de Publicação',
                    'required' => true,
                    'regex' => $regex['ano_publicacao']
                ],
                'isbn' => [
                    'label' => 'ISBN',
                    'required' => false,
                    'min' => 3,
                    'max' => 64
                ],
                'edicao' => [
                    'label' => 'Edição',
                    'required' => false,
                    'regex' => $regex['edicao']
                ],
                'idioma' => [
                    'label' => 'Idioma',
                    'required' => true,
                    'min' => 3,
                    'max' => 128,
                    'regex' => $regex['idioma']
                ],
                'paginas' => [
                    'label' => 'Páginas',
                    'required' => false,
                    'regex' => $regex['paginas']
                ],
                'descricao' => [
                    'label' => 'Descrição',
                    'required' => false,
                    'max' => 6000
                ]
            ];

            if (!empty($formRequest)) {
                $fields = [
                    'titulo',
                    'autor',
                    'editora',
                    'formato',
                    'ano_publicacao',
                    'isbn',
                    'edicao',
                    'idioma',
                    'paginas',
                    'descricao'
                ];

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                if (strlen(trim($formRequest['autor'])) > 0) {
                    $autores = explode(',', $formRequest['autor']);

                    foreach ($autores as $key => $value) {
                        $autores[$key] = trim($autores[$key]);

                        if (!self::validateAutorOrEditoraName($autores[$key], $regex['autor']))
                            $this->validator->addError('O campo "Autor(es)" está inválido.');
                    }
                } else $this->validator->addError('O campo "Autor(es)" é obrigatório.');

                if (strlen(trim($formRequest['editora'])) > 0) {
                    $editoras = explode(',', $formRequest['editora']);

                    foreach ($editoras as $key => $value) {
                        $editoras[$key] = trim($editoras[$key]);

                        if (!self::validateAutorOrEditoraName($editoras[$key], $regex['editora']))
                            $this->validator->addError('O campo "Editora(s)" está inválido.');
                    }
                } else $this->validator->addError('O campo "Editora(s)" é obrigatório.');

                if ($this->validator->passed()) {
                    $dataWrite = [
                        'titulo' => $formRequest['titulo'] ?? null,
                        'formato' => $formRequest['formato'] ?? null,
                        'ano_publicacao' => $formRequest['ano_publicacao'] ?? null,
                        'isbn' => $formRequest['isbn'] ?? null,
                        'edicao' => DataFilter::isInteger($formRequest['edicao']) ?? null,
                        'idioma' => $formRequest['idioma'] ?? null,
                        'paginas' => DataFilter::isInteger($formRequest['paginas']) ?? null,
                        'descricao' => $formRequest['descricao'] ?? null,
                        'registrado_em' => date('Y-m-d H:i:s') ?? null
                    ];

                    $this->livro->setTitutlo($dataWrite['titulo']);
                    $this->livro->setFormato($dataWrite['formato']);
                    $this->livro->setAnoPublicacao($dataWrite['ano_publicacao']);
                    $this->livro->setIsbn($dataWrite['isbn']);
                    $this->livro->setEdicao($dataWrite['edicao']);
                    $this->livro->setIdioma($dataWrite['idioma']);
                    $this->livro->setPaginas($dataWrite['paginas']);
                    $this->livro->setDescricao($dataWrite['descricao']);
                    $this->livro->setRegistradoEm($dataWrite['registrado_em']);

                    $IDLivro = $this->livroDAO->register($this->livro);
                    if ($IDLivro !== []) {
                        foreach ($autores as $key => $value) {
                            $autores[$key] = trim($autores[$key]);

                            $this->autor->setNome($autores[$key]);

                            if (($autor = $this->autorDAO->getAutorByNome($this->autor)) === [])
                                $IDAutor = $this->autorDAO->register($this->autor);
                            else $IDAutor = $autor[0]['ID'];

                            $this->livroAutor->setIDLivro($IDLivro);
                            $this->livroAutor->setIDAutor($IDAutor);
                            $this->livroAutorDAO->register($this->livroAutor);
                        }

                        foreach ($editoras as $key => $value) {
                            $editoras[$key] = trim($editoras[$key]);

                            $this->editora->setNome($editoras[$key]);

                            if (($editora = $this->editoraDAO->getEditoraByNome($this->editora)) === [])
                                $IDEditora = $this->editoraDAO->register($this->editora);
                            else $IDEditora = $editora[0]['ID'];

                            $this->livroEditora->setIDLivro($IDLivro);
                            $this->livroEditora->setIDEditora($IDEditora);
                            $this->livroEditoraDAO->register($this->livroEditora);
                        }

                        $this->container->get('flash')
                            ->addMessage('message.success', 'Livro cadastrado com sucesso!');

                        $url = RouteContext::fromRequest($request)
                            ->getRouteParser()
                            ->urlFor('livros.index');

                        return $response
                            ->withHeader('Location', $url)
                            ->withStatus(302);
                    } else $errors = (array)'Houve um erro inesperado.';
                } else $errors = array_unique($this->validator->errors());
            }
        }

        $formRequest = $formRequest ?? [];
        $persistRegisterValues = self::getPersistRegisterValues($formRequest);
        $errors = $errors ?? [];

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'persistRegisterValues' => $persistRegisterValues,
            'errors' => $errors
        ];

        return $this->renderer->render($response, 'dashboard/livros/register.php', $templateVariables);
    }

    public function update(Request $request, Response $response, array $args): Response
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
                ->withHeader('Locaion', $url)
                ->withStatus(302);
        }

        $this->livro->setID($ID);
        if ($this->livroDAO->getLivroByID($this->livro) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('dashboard.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $livro = $this->livroDAO->getLivroByID($this->livro)[0];

        if ($request->getMethod() == 'POST') {
            if ($ID !== Session::get('livro.update.ID')) {
                $url = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('livros.update', ['ID' => Session::get('livro.update.ID')]);

                return $response
                    ->withHeader('Location', $url)
                    ->withStatus(302);
            }

            Session::delete('livro.update.ID');

            $formRequest = (array)$request->getParsedBody();

            $regex = [
                'autor' => '/^[A-Za-z .\'][^0-9,=]+$/',
                'editora' => '/^[A-Za-z0-9 .\'][^,=]+$/',
                'ano_publicacao' => '/^19[0-9][0-9]|20[01][0-9]|202[0-3]$/',
                'edicao' => '/^([1-9]|[0-9][0-9])$/',
                'idioma' => '/^[A-Za-z \'][^0-9.,]+$/',
                'paginas' => '/^([1-9]|[1-9][0-9]{1,4}|1[0-9]{5}|200000)$/'
            ];

            $rules = [
                'titulo' => [
                    'label' => 'Título',
                    'required' => true,
                    'min' => 3,
                    'max' => 255
                ],
                'formato' => [
                    'label' => 'Formato',
                    'required' => false,
                    'min' => 3,
                    'max' => 128
                ],
                'ano_publicacao' => [
                    'label' => 'Ano de Publicação',
                    'required' => true,
                    'regex' => $regex['ano_publicacao']
                ],
                'isbn' => [
                    'label' => 'ISBN',
                    'required' => false,
                    'min' => 3,
                    'max' => 64
                ],
                'edicao' => [
                    'label' => 'Edição',
                    'required' => false,
                    'regex' => $regex['edicao']
                ],
                'idioma' => [
                    'label' => 'Idioma',
                    'required' => true,
                    'min' => 3,
                    'max' => 128,
                    'regex' => $regex['idioma']
                ],
                'paginas' => [
                    'label' => 'Páginas',
                    'required' => false,
                    'regex' => $regex['paginas']
                ],
                'descricao' => [
                    'label' => 'Descrição',
                    'required' => false,
                    'max' => 6000
                ]
            ];

            if (!empty($formRequest)) {
                $fields = [
                    'titulo',
                    'autor',
                    'editora',
                    'formato',
                    'ano_publicacao',
                    'isbn',
                    'edicao',
                    'idioma',
                    'paginas',
                    'descricao'
                ];

                $persistUpdateValues = self::getPersistUpdateValues($livro, $formRequest);

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                $autores = explode(',', $formRequest['autor']);
                $editoras = explode(',', $formRequest['editora']);

                if (strlen(trim($formRequest['autor'])) > 0) {
                    $autores = explode(',', $formRequest['autor']);

                    foreach ($autores as $key => $value) {
                        $autores[$key] = trim($autores[$key]);

                        if (!self::validateAutorOrEditoraName($autores[$key], $regex['autor']))
                            $this->validator->addError('O campo "Autor(es)" está inválido.');
                    }
                } else $this->validator->addError('O campo "Autor(es)" é obrigatório.');

                if (strlen(trim($formRequest['editora'])) > 0) {
                    $editoras = explode(',', $formRequest['editora']);

                    foreach ($editoras as $key => $value) {
                        $editoras[$key] = trim($editoras[$key]);

                        if (!self::validateAutorOrEditoraName($editoras[$key], $regex['editora']))
                            $this->validator->addError('O campo "Editora(s)" está inválido.');
                    }
                } else $this->validator->addError('O campo "Editora(s)" é obrigatório.');

                if ($this->validator->passed()) {
                    $dataWrite = [
                        'titulo' => $formRequest['titulo'] ?? null,
                        'formato' => $formRequest['formato'] ?? null,
                        'ano_publicacao' => $formRequest['ano_publicacao'] ?? null,
                        'isbn' => $formRequest['isbn'] ?? null,
                        'edicao' => DataFilter::isInteger($formRequest['edicao']) ?? null,
                        'idioma' => $formRequest['idioma'] ?? null,
                        'paginas' => DataFilter::isInteger($formRequest['paginas']) ?? null,
                        'descricao' => $formRequest['descricao'] ?? null
                    ];

                    $this->livro->setTitutlo($dataWrite['titulo']);
                    $this->livro->setFormato($dataWrite['formato']);
                    $this->livro->setAnoPublicacao($dataWrite['ano_publicacao']);
                    $this->livro->setIsbn($dataWrite['isbn']);
                    $this->livro->setEdicao($dataWrite['edicao']);
                    $this->livro->setIdioma($dataWrite['idioma']);
                    $this->livro->setPaginas($dataWrite['paginas']);
                    $this->livro->setDescricao($dataWrite['descricao']);

                    $this->livroAutor->setIDLivro($ID);
                    $autoresFromDelete = $this->livroAutorDAO->getLivroAutorByIDLivro($this->livroAutor);

                    $this->livroEditora->setIDLivro($ID);
                    $editorasFromDelete = $this->livroEditoraDAO->getLivroEditoraByIDLivro($this->livroEditora);

                    if ($autoresFromDelete !== []) {
                        $this->livroAutorDAO->deleteLivroAutorByIDLivro($this->livroAutor);

                        foreach ($autoresFromDelete as $autorFromDelete) {
                            $this->livroAutor->setIDLivro($ID);
                            $this->livroAutor->setIDAutor($autorFromDelete['ID_autor']);
                            $livroAutor = $this->livroAutorDAO->getLivroAutorByOtherIDLivroAndByIDAutor($this->livroAutor);

                            if ($livroAutor === []) {
                                $this->autor->setID($autorFromDelete['ID_autor']);
                                $this->autorDAO->deleteAutorByID($this->autor);
                            }
                        }
                    }

                    foreach ($autores as $key => $value) {
                        $autores[$key] = trim($autores[$key]);

                        $this->autor->setNome($autores[$key]);
                        $autor = $this->autorDAO->getAutorByNome($this->autor);

                        if ($autor === []) $IDAutor = $this->autorDAO->register($this->autor);
                        else $IDAutor = $autor[0]['ID'];

                        $this->livroAutor->setIDLivro($ID);
                        $this->livroAutor->setIDAutor($IDAutor);
                        $this->livroAutorDAO->register($this->livroAutor);
                    }

                    if ($editorasFromDelete !== []) {
                        $this->livroEditoraDAO->deleteLivroEditoraByIDLivro($this->livroEditora);

                        foreach ($editorasFromDelete as $editoraFromDelete) {
                            $this->livroEditora->setIDLivro($ID);
                            $this->livroEditora->setIDEditora($editoraFromDelete['ID_editora']);
                            $livroEditora = $this->livroEditoraDAO->getLivroEditoraByOtherIDLivroAndByIDEditora($this->livroEditora);

                            if ($livroEditora === []) {
                                $this->editora->setID($editoraFromDelete['ID_editora']);
                                $this->editoraDAO->deleteEditoraByID($this->editora);
                            }
                        }
                    }

                    foreach ($editoras as $key => $value) {
                        $editoras[$key] = trim($editoras[$key]);

                        $this->editora->setNome($editoras[$key]);
                        $editora = $this->editoraDAO->getEditoraByNome($this->editora);

                        if ($editora === []) $IDEditora = $this->editoraDAO->register($this->editora);
                        else $IDEditora = $editora[0]['ID'];

                        $this->livroEditora->setIDLivro($ID);
                        $this->livroEditora->setIDEditora($IDEditora);
                        $this->livroEditoraDAO->register($this->livroEditora);
                    }

                    if ($this->livroDAO->update($this->livro)) {
                        $this->container->get('flash')
                            ->addMessage('message.success', 'Livro atualizado com sucesso!');

                        $url = RouteContext::fromRequest($request)
                            ->getRouteParser()
                            ->urlFor('livros.show', ['ID' => $ID]);

                        return $response
                            ->withHeader('Location', $url)
                            ->withStatus(302);
                    } else $errors = (array)'Houve um erro inesperado.';
                } else $errors = array_unique($this->validator->errors());
            }
        }

        Session::create('livro.update.ID', $ID);

        $this->livroAutor->setIDLivro($ID);
        $autor['items'] = $this->livroAutorDAO->getAutorByIDLivro($this->livroAutor);
        $livro['autor'] = self::autoresOrEditorasToString($autor['items']);

        $this->livroEditora->setIDLivro($ID);
        $editora['items'] = $this->livroEditoraDAO->getEditoraByIDLivro($this->livroEditora);
        $livro['editora'] = self::autoresOrEditorasToString($editora['items']);

        $persistUpdateValues = $persistUpdateValues ?? $livro;
        $errors = $errors ?? [];

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'livro' => $livro,
            'errors' => $errors,
            'persistUpdateValues' => $persistUpdateValues
        ];

        return $this->renderer->render($response, 'dashboard/livros/update.php', $templateVariables);
    }

    private static function validateAutorOrEditoraName($value, $regexRule)
    {
        return (preg_match($regexRule, $value)) ? true : false;
    }

    private static function autoresOrEditorasToString($array)
    {
        $string = '';

        foreach ($array as $item) {
            $string .= $item['nome'] . ', ';
        }

        $string = rtrim($string, ', ');

        return $string;
    }

    private static function getPersistRegisterValues($request)
    {
        foreach ($request as $key => $value) {
            if ($value !== '') $request[$key] = $value;
            else $request[$key] = null;
        }

        return $request;
    }

    private static function getPersistUpdateValues($data, $request)
    {
        foreach ($request as $key => $value) {
            if (
                (isset($data[$key])) &&
                ($data[$key] !== '')
            ) $data[$key] = $request[$key];
            if ($request[$key] === '') $request[$key] = null;
        }

        return $request;
    }
}
