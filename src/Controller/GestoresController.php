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
            $gestorProfile = parent::applyGestorData($gestorProfile);
        }

        if (
            ($gestor['ID'] !== $gestorProfile['ID']) &&
            ($privilege === true)
        ) $authorize['status'] = true;
        else $authorize['status'] = false;

        if (
            ($gestor['ID'] == $gestorProfile['ID'])
        ) $authorize['update']['current'] = true;
        else $authorize['update']['current'] = false;

        if (
            ($gestor['ID'] !== $gestorProfile['ID']) &&
            ($privilege === true)
        ) $authorize['update']['admin'] = true;
        else $authorize['update']['admin'] = false;

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
                'name' => '/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.\'-]+$/' // super sweet unicode
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
                    
                ],
                'cpf' => [],
                'telefone' => [],
                'endereco' => [],
                'cargo' => [],
                'genero' => [],
                'status' => []
            ];

            if (!empty($formRequest)) {
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
            $gestorProfile = parent::applyGestorData($gestorProfile);
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
            'errors' => $errors
        ];

        return $this->renderer->render($response, 'dashboard/gestores/update.php', $templateVariables);
    }
}
