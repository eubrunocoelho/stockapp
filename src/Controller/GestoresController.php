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
    Validator\Validate
};

use App\{
    Model\Gestor,
    Model\GestorDAO
};

use PDO;

class GestoresController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validate;

    private
        $gestor, $gestorDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validate = $this->container->get(Validate::class);

        $this->gestor = new Gestor();
        $this->gestorDAO = new GestorDAO($this->database);

        parent::__construct($this->app);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $ID = $request->getAttribute('ID');
        $basePath = $this->container->get('settings')['api']['path'];
        $gestor = parent::getGestor();

        if ($gestor['cargo'] === 1) $status['active'] = 'administrador';
        else $status['active'] = 'gestor';

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

        $this->gestor->setID($ID);
        if ($this->gestorDAO->getGestorByID($this->gestor) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('dashboard.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        } else {
            $gestorProfile = $this->gestorDAO->getGestorByID($this->gestor)[0];

            if ($gestorProfile['cargo'] === 1) $status['profile'] = 'administrador';
            else $status['profile'] = 'gestor';

            $gestorProfile = parent::applyGestorData($gestorProfile);
        }

        dd($status);

        if (
            ($status['active'] === 'administrador') &&
            ($status['profile'] === 'administrador')
        ) {
            $authorize['update']['profile'] = false;
            $authorize['update']['status'] = false;
        }

        if (
            ($status['active'] === 'administrador') &&
            ($status['profile'] === 'gestor') &&
            ($gestor['ID'] !== $gestorProfile['ID'])
        ) {
            $authorize['update']['profile'] = true;
            $authorize['update']['status'] = true;
        }

        if ($gestor['ID'] === $gestorProfile['ID']) {
            $authorize['update']['profile'] = true;
            $authorize['update']['status'] = false;
        }

        dd($authorize);

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'gestorProfile' => $gestorProfile,
            'authorize' => $authorize

        ];

        return $this->renderer->render($response, 'dashboard/gestores/show.php', $templateVariables);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $ID = $request->getAttribute('ID');

        $this->gestor->setID($ID);
        if ($this->gestorDAO->getGestorByID($this->gestor) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('dashboard.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        } else $gestorProfile = $this->gestorDAO->getGestorByID($this->gestor)[0];


        if ($request->getMethod() == 'POST') {
            if ($ID !== Session::get('update.ID')) {
                $url = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('gestores.update', ['ID' => Session::get('update.ID')]);

                return $response
                    ->withHeader('Location', $url)
                    ->withStatus(302);
            }

            Session::delete('update.ID');

            $formRequest = (array)$request->getParsedBody();

            $regex = [
                'name' => '/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.\'-]+$/', // super sweet unicode
                'cargo' => '/^[1-2]{1}$/',
                'genero' => '/^[1-2]{1}$/',
                'status' => '/^[1-2]{1}$/'
            ];

            $rules = [
                'nome' => [
                    'label' => 'Nome',
                    'required' => true,
                    'min' => 3,
                    'max' => 128,
                    'regex' => $regex['name']
                ],
                'email' => [
                    'label' => 'Email',
                    'required' => true,
                    'max' => 128,
                    'email' => true
                ],
                'cpf' => [
                    'label' => 'CPF',
                    'required' => true,
                    'cpf' => true
                ],
                'telefone' => [
                    'label' => 'Telefone',
                    'required' => false,
                    'telephone' => true
                ],
                'endereco' => [
                    'label' => 'Endereço',
                    'required' => false,
                    'min' => 6,
                    'max' => 255
                ],
                'cargo' => [
                    'label' => 'Cargo',
                    'required' => true,
                    'regex' => $regex['cargo']
                ],
                'genero' => [
                    'label' => 'Gênero',
                    'required' => true,
                    'regex' => $regex['genero']
                ],
                'status' => [
                    'label' => 'Status',
                    'required' => true,
                    'regex' => $regex['status']
                ]
            ];

            if (!empty($formRequest)) {
                $persistUpdateValues = $this->getPersistUpdateValues($formRequest, $gestorProfile);

                $this->validate->setFields(
                    [
                        'nome',
                        'email',
                        'cpf',
                        'telefone',
                        'endereco',
                        'cargo',
                        'genero',
                        'status'
                    ]
                );
                $this->validate->setData($formRequest);
                $this->validate->setRules($rules);
                $this->validate->validation();

                if (!$this->validate->passed()) $errors = $this->validate->errors();
            }
        }

        $errors = $errors ?? [];
        $persistUpdateValues = $persistUpdateValues ?? $gestorProfile;
        $gestorProfile = parent::applyGestorData($gestorProfile);

        Session::create('update.ID', $ID);

        $basePath = $this->container->get('settings')['api']['path'];
        $gestor = parent::getGestor();

        if ($gestor['cargo'] === 1) $privilege = true;
        else $privilege = false;

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

        if (
            ($gestor['ID'] == $gestorProfile['ID'])
        ) $authorize['update']['current'] = true;
        else $authorize['update']['current'] = false;

        if (
            ($gestor['ID'] !== $gestorProfile['ID']) &&
            ($privilege === true)
        ) $authorize['update']['admin'] = true;
        else $authorize['update']['admin'] = false;

        if (
            !(($authorize['update']['current']) ||
                ($authorize['update']['admin']))
        ) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('dashboard.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'gestorProfile' => $gestorProfile,
            'persistUpdateValues' => $persistUpdateValues,
            'errors' => $errors
        ];

        return $this->renderer->render($response, 'dashboard/gestores/update.php', $templateVariables);
    }

    public static function getPersistUpdateValues($request, $data)
    {
        unset($data['ID'], $data['senha'], $data['img_url']);

        foreach ($data as $key => $value) {
            if ($data[$key] != $request[$key]) $request[$key] = $request[$key];
            else $request[$key] = $data[$key];
        }

        return $request;
    }
}
