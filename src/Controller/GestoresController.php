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
        $gestor = parent::getGestor();

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

            // if ($gestor['cargo'] === 1) $status['active'] = 'administrador';
            // else $status['active'] = 'gestor';

            if ($gestor['cargo'] === 1) $status['active'] = 1;
            else $status['active'] = 2;
        }

        // if ($gestorProfile['cargo'] === 1) $status['profile'] = 'administrador';
        // else $status['profile'] = 'gestor';

        if ($gestorProfile['cargo'] === 1) $status['profile'] = 1;
        else $status['profile'] = 2;

        $authorize = self::authorize($gestor, $gestorProfile, $status);

        // if (
        //     ($status['active'] === 'administrador') &&
        //     ($status['profile'] === 'administrador')
        // ) {
        //     $authorize['update']['profile'] = false;
        //     $authorize['update']['status'] = false;
        // }

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
                ]
            ];

            if ($authorize['update']['status']) {
                $rules['status'] = [
                    'label' => 'Status',
                    'required' => true,
                    'regex' => $regex['status']
                ];
            }

            if (!empty($formRequest)) {
                $fields = [
                    'nome',
                    'email',
                    'cpf',
                    'telefone',
                    'endereco',
                    'cargo',
                    'genero'
                ];

                if ($authorize['update']['status']) {
                    $fields[] = 'status';
                }

                $persistUpdateValues = self::getPersistUpdateValues($gestorProfile, $formRequest);

                $this->validate->setFields($fields);
                $this->validate->setData($formRequest);
                $this->validate->setRules($rules);
                $this->validate->validation();

                if (!$this->validate->passed()) {
                    $errors = $this->validate->errors();
                }
            }
        }

        $persistUpdateValues = $persistUpdateValues ?? $gestorProfile;

        // dd($persistUpdateValues, true);

        $errors = $errors ?? [];

        $gestorProfile = parent::applyGestorData($gestorProfile);

        Session::create('update.ID', $ID);

        $basePath = $this->container->get('settings')['api']['path'];

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
            !($authorize['update']['status']) &&
            !($authorize['update']['profile'])
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
            'authorize' => $authorize,
            'errors' => $errors,
            'persistUpdateValues' => $persistUpdateValues
        ];

        return $this->renderer->render($response, 'dashboard/gestores/update.php', $templateVariables);
    }

    private static function getPersistUpdateValues($data, $request)
    {
        foreach ($request as $key => $value) {
            if (
                (isset($data[$key])) &&
                ($data[$key] !== '')
            ) {
                $data[$key] = $request[$key];
            }

            if ($request[$key] == '') {
                $request[$key] = null;
            }
        }

        return $request;
    }

    private static function authorize($gestor, $gestorProfile, $status)
    {
        $userAdminProfileAdmin =
            ($status['active'] === 1) &&
            ($status['profile'] === 1) &&
            ($gestor['ID'] !== $gestorProfile['ID']);

        $userAdminProfileGestor =
            ($status['active'] === 1) &&
            ($status['profile'] === 2) &&
            ($gestor['ID'] !== $gestorProfile['ID']);

        $currentProfile =
            ($gestor['ID'] === $gestorProfile['ID']);

        switch ($status) {
            case ($userAdminProfileAdmin): {
                    $authorize['update']['profile'] = false;
                    $authorize['update']['status'] = false;
                }

                break;
            case ($userAdminProfileGestor): {
                    $authorize['update']['profile'] = true;
                    $authorize['update']['status'] = true;
                }

                break;
            case ($currentProfile): {
                    $authorize['update']['profile'] = true;
                    $authorize['update']['status'] = false;
                }

                break;
        }

        return $authorize;
    }
}
