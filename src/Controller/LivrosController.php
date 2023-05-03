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
    Model\Autor,
    Model\AutorDAO,
    Model\LivroAutor,
    Model\LivroAutorDAO
};

use PDO;

class LivrosController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    private
        $livro, $livroDAO, $autor, $autorDAO, $livroAutor, $livroAutorDAO;

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
        $this->livroAutor = new LivroAutor();
        $this->livroAutorDAO = new LivroAutorDAO($this->database);

        parent::__construct($this->app);
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
                'autor' => '/^[A-Za-z .\'][^0-9-,]+$/',
                'editora' => '/^[A-Za-z0-9 .\'][^,]+$/',
                'ano_publicacao' => '/^19[0-9][0-9]|20[01][0-9]|202[0-3]$/',
                'edicao' => '/^([1-9]|[0-9][0-9])$/',
                'idioma' => '/^[A-Za-z \'][^0-9.,]+$/',
                'paginas' => '/^([1-9]|[1-9][0-9]{1,4}|1[0-9]{5}|200000)$/',
                'unidades' => '/^([0-9]|[1-9][0-9]{1,3}|[1-4][0-9]{4}|20000)$/'
            ];

            $rules = [
                'titulo' => [
                    'label' => 'Título',
                    'required' => true,
                    'min' => 3,
                    'max' => 255
                ],
                'autor' => [
                    'label' => 'Autor(es)',
                    'required' => true,
                    'min' => 3,
                    'max' => 255
                ],
                'editora' => [
                    'label' => 'Editora(s)',
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
                    'required' => true,
                    'unique' => 'isbn|livro',
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
                ],
                'unidades' => [
                    'label' => 'Unidades',
                    'required' => true,
                    'regex' => $regex['unidades']
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
                    'descricao',
                    'unidades'
                ];

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                $autores = explode(',', $formRequest['autor']);
                $editoras = explode(',', $formRequest['editora']);

                foreach ($autores as $key => $value) {
                    $autores[$key] = trim($autores[$key]);

                    if (!self::validateAutorName($autores[$key], $regex['autor']))
                        $this->validator->addError('O campo "Autor(es)" está inválido.');
                }

                foreach ($editoras as $key => $value) {
                    $editoras[$key] = trim($editoras[$key]);

                    if (!self::validateEditoraName($editoras[$key], $regex['editora']))
                        $this->validator->addError('O campo "Editora(s)" está inválido.');
                }

                if ($this->validator->passed()) {
                    $dataWrite = [
                        'titulo' => $formRequest['titulo'] ?? null,
                        'formato' => $formRequest['formato'] ?? null,
                        'ano_publicacao' => $formRequest['ano_publicacao'] ?? null,
                        'isbn' => $formRequest['isbn'] ?? null,
                        'edicao' => intval($formRequest['edicao']) ?? null,
                        'idioma' => $formRequest['idioma'] ?? null,
                        'paginas' => intval($formRequest['paginas']) ?? null,
                        'descricao' => $formRequest['descricao'] ?? null,
                        'unidades' => intval($formRequest['unidades']) ?? null
                    ];

                    $this->livro->setTitutlo($dataWrite['titulo']);
                    $this->livro->setFormato($dataWrite['formato']);
                    $this->livro->setAnoPublicacao($dataWrite['ano_publicacao']);
                    $this->livro->setIsbn($dataWrite['isbn']);
                    $this->livro->setEdicao($dataWrite['edicao']);
                    $this->livro->setIdioma($dataWrite['idioma']);
                    $this->livro->setPaginas($dataWrite['paginas']);
                    $this->livro->setDescricao($dataWrite['descricao']);
                    $this->livro->setUnidades($dataWrite['unidades']);

                    if (($IDLivro = $this->livroDAO->register($this->livro)) !== []) {
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

                        
                    }
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

    private static function validateAutorName($autor, $regexRule)
    {
        return (preg_match($regexRule, $autor)) ? true : false;
    }

    private static function validateEditoraName($editora, $regexRule)
    {
        return (preg_match($regexRule, $editora)) ? true : false;
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
