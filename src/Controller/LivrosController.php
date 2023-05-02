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

use PDO;

class LivrosController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);

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
                'ano_publicacao' => '/^19[0-9][0-9]|20[01][0-9]|202[0-3]$/',
                'edicao' => '/^([1-9]|[0-9][0-9])$/',
                'idioma' =>
                // super sweet unicode
                '/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð,.\'-]+$/',
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

                if ($this->validator->passed()) {
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

    private static function getPersistRegisterValues($request)
    {
        foreach ($request as $key => $value) {
            if ($value !== '') $request[$key] = $value;
            else $request[$key] = null;
        }

        return $request;
    }
}
