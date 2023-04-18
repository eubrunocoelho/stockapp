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
    Model\Gestor,
    Model\GestorDAO
};

use PDO;

class GestoresController extends GestorController
{
    private
        $app, $container, $database, $renderer, $validator;

    private
        $gestor, $gestorDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);
        $this->validator = $this->container->get(Validator::class);

        $this->gestor = new Gestor();
        $this->gestorDAO = new GestorDAO($this->database);

        parent::__construct($this->app);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $ID = $request->getAttribute('ID');
        $basePath = $this->container->get('settings')['api']['path'];
        $gestor = parent::getGestor();

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
        }

        $gestorProfile = $this->gestorDAO->getGestorByID($this->gestor)[0];
        $authorize = self::authorize($gestor, $gestorProfile);
        $gestor = parent::applyGestorData($gestor);
        $gestorProfile = parent::applyGestorData($gestorProfile);


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
        $basePath = $this->container->get('settings')['api']['path'];
        $uploadDirectory =
            $this->container->get('settings')['api']['uploadDirectory'] . '/img/profile';

        $gestor = parent::getGestor();

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
        }

        $gestorProfile = $this->gestorDAO->getGestorByID($this->gestor)[0];
        $authorize = self::authorize($gestor, $gestorProfile);

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

            $uploadedFiles = $request->getUploadedFiles();
            $uploadedFile = ($uploadedFiles['img_profile']) ?? [];

            if ($uploadedFile !== []) {
                $uploadRules = [
                    'mimeTypes' => [
                        'image/gif',
                        'image/jpeg',
                        'image/png'
                    ],
                    'maxSize' => 2097152
                ];

                if (!self::validateMediaType($uploadedFile, $uploadRules)) dd('error');
                if (!self::validateFileSize($uploadedFile, $uploadRules)) dd('error');

                if (
                    (self::validateMediaType($uploadedFile, $uploadRules)) &&
                    (self::validateFileSize($uploadedFile, $uploadRules))
                ) {
                    if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0777, true);
                    if ($uploadedFile->getError() === UPLOAD_ERR_OK)
                        self::moveUploadedFile($uploadDirectory, $uploadedFile);
                }
            }

            $regex = [
                'name' =>
                // super sweet unicode
                '/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.\'-]+$/',
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
                    'label' => 'E-mail',
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

                if ($authorize['update']['status']) $fields[] = 'status';

                $persistUpdateValues = self::getPersistUpdateValues($gestorProfile, $formRequest);

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                if (!$this->validator->passed()) {
                    $errors = $this->validator->errors();
                }
            }
        }

        Session::create('update.ID', $ID);

        $gestor = parent::applyGestorData($gestor);
        $persistUpdateValues = $persistUpdateValues ?? $gestorProfile;
        $gestorProfile = parent::applyGestorData($gestorProfile);
        $errors = $errors ?? [];

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
            'persistUpdateValues' => $persistUpdateValues,
            'errors' => $errors
        ];

        return $this->renderer->render($response, 'dashboard/gestores/update.php', $templateVariables);
    }

    private static function authorize($gestor, $gestorProfile)
    {
        $status['active'] = ($gestor['cargo'] === 1) ? 1 : 2;
        $status['profile'] = ($gestorProfile['cargo'] === 1) ? 1 : 2;

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
            case ($userAdminProfileAdmin):
                $authorize['update']['profile'] = false;
                $authorize['update']['status'] = false;
                break;
            case ($userAdminProfileGestor):
                $authorize['update']['profile'] = true;
                $authorize['update']['status'] = true;
                break;
            case ($currentProfile):
                $authorize['update']['profile'] = true;
                $authorize['update']['status'] = false;
                break;
        }

        return $authorize;
    }

    private static function validateMediaType($uploadedFile, $uploadRules)
    {
        $fileMediaType = $uploadedFile->getClientMediaType();

        if (!in_array($fileMediaType, $uploadRules['mimeTypes']))
            return false;

        return true;
    }

    private static function validateFileSize($uploadedFile, $uploadRules)
    {
        $fileSize = $uploadedFile->getSize();

        if ($fileSize > $uploadRules['maxSize'])
            return false;

        return true;
    }

    private static function moveUploadedFile($uploadDirectory, $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $baseName = bin2hex(random_bytes(8));
        $fileName = $baseName . '.' . $extension;
        $uploadedFile->moveTo($uploadDirectory . DIRECTORY_SEPARATOR . $fileName);

        return true;
    }

    private static function getPersistUpdateValues($data, $request)
    {
        foreach ($request as $key => $value) {
            if (
                (isset($data[$key])) &&
                ($data[$key] !== '')
            ) $data[$key] = $request[$key];

            if ($request[$key] == '') $request[$key] = null;
        }

        return $request;
    }
}
