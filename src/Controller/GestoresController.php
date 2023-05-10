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

    public function index(Request $request, Response $response, array $args): Response
    {
        $flash = $this->container->get('flash');
        $messages = $flash->getMessages();

        $basePath = $this->container->get('settings')['api']['path'];
        $gestor = parent::getGestor();
        $authorize = self::authorize('register', $gestor);
        $gestor = parent::applyGestorData($gestor);

        $URI = (array)$request->getQueryParams();
        
        $pagination['currentPage'] = $URI['page'] ?? 1;
        $pagination['resultLimit'] = 5;
        $pagination['start'] = ($pagination['resultLimit'] * $pagination['currentPage']) - $pagination['resultLimit'];
        $pagination['totalRegisters'] = $this->gestorDAO->getTotalRegisters()[0]['total_registros'];
        $pagination['totalPages'] = ceil($pagination['totalRegisters'] / $pagination['resultLimit']);

        if ($pagination['currentPage'] == 1) $pagination['links']['previous'] = false;
        else $pagination['links']['previous'] = true;

        if ($pagination['currentPage'] == $pagination['totalPages']) $pagination['links']['next'] = false;
        else $pagination['links']['next'] = true;

        $gestores = $this->gestorDAO->getAllWithPagination($pagination);


        foreach ($gestores as $key => $value)
            $gestores[$key] = parent::applyGestorData($gestores[$key]);


        if ($gestor === []) {
            Session::destroy();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'gestores' => $gestores,
            'authorize' => $authorize,
            'messages' => $messages,
            'pagination' => $pagination
        ];

        return $this->renderer->render($response, 'dashboard/gestores/index.php', $templateVariables);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $flash = $this->container->get('flash');
        $messages = $flash->getMessages();

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

        // ID da URL
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
        $authorize = self::authorize('update', $gestor, $gestorProfile);

        $gestor = parent::applyGestorData($gestor);
        $gestorProfile = parent::applyGestorData($gestorProfile);


        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'gestorProfile' => $gestorProfile,
            'authorize' => $authorize,
            'messages' => $messages
        ];

        return $this->renderer->render($response, 'dashboard/gestores/show.php', $templateVariables);
    }

    public function register(Request $request, Response $response, array $args): Response
    {
        $basePath = $this->container->get('settings')['api']['path'];
        $gestor = parent::getGestor();
        $uploadDirectory =
            $this->container->get('settings')['api']['uploadDirectory'] . '/img/profile';

        if ($gestor === []) {
            Session::destroy();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $authorize = self::authorize('register', $gestor);

        if (!($authorize['register'])) {
            $this->container->get('flash')
                ->addMessage('message.warning', 'Você não tem permissão para executar essa ação.');

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('gestores.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $gestor = parent::applyGestorData($gestor);

        if ($request->getMethod() == 'POST') {
            $formRequest = (array)$request->getParsedBody();

            $regex = [
                'name' => '/^[A-Za-z .\'][^0-9,]+$/',
                'cargo' => '/^[1-2]{1}$/',
                'cpf' => '/^([0-9]){3}\.([0-9]){3}\.([0-9]){3}-([0-9]){2}$/',
                'telefone' => '/^\([0-9]{2}\) [0-9]?[0-9]{4}-[0-9]{4}$/',
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
                    'email' => true,
                    'unique' => 'email|gestor',
                    'max' => 128
                ],
                'cpf' => [
                    'label' => 'CPF',
                    'required' => true,
                    'cpf' => true,
                    'unique' => 'cpf|gestor',
                    'regex' => $regex['cpf']
                ],
                'senha' => [
                    'label' => 'Senha',
                    'required' => true,
                    'min' => 6,
                    'max' => 22
                ],
                'telefone' => [
                    'label' => 'Telefone',
                    'required' => false,
                    'regex' => $regex['telefone']
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
                $fields = [
                    'nome',
                    'email',
                    'cpf',
                    'senha',
                    'telefone',
                    'endereco',
                    'cargo',
                    'genero',
                    'status'
                ];

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                if ($this->validator->passed()) {
                    $uploadedFiles = $request->getUploadedFiles();
                    $uploadedFile = $uploadedFiles['img_profile'] ?? [];

                    if (
                        ($uploadedFile !== []) &&
                        ($uploadedFile->getClientFilename() !== '')
                    ) {
                        $uploadRules = [
                            'mimeTypes' => [
                                'image/gif',
                                'image/jpeg',
                                'image/png'
                            ],
                            'maxSize' => 2097152 //2097152
                        ];

                        if (!self::validateMediaType($uploadedFile, $uploadRules))
                            $errors = (array)'O formato de arquivo para imagem de "Foto de Perfil" não é compatível.';

                        if (!self::validateFileSize($uploadedFile, $uploadRules))
                            $errors = (array)'O tamanho de arquivo para imagem de "Foto de Perfil" excede o permitido.';

                        if (
                            (self::validateMediaType($uploadedFile, $uploadRules)) &&
                            (self::validateFileSize($uploadedFile, $uploadRules))
                        ) {
                            $uploadedFile = $uploadedFile;
                            $uploadFileName = self::renameFile($uploadedFile);
                        }
                    }

                    $uploadedFile = $uploadedFile ?? null;
                    $uploadFileName = $uploadFileName ?? null;

                    if (($uploadFileName !== null)) {
                        if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0777, true);

                        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                            self::moveUploadedFile($uploadDirectory, $uploadedFile, $uploadFileName);
                        } else $errors = (array)'Houve um erro inesperado.';
                    }

                    $dataWrite = [
                        'nome' => $formRequest['nome'] ?? null,
                        'email' => $formRequest['email'] ?? null,
                        'cpf' => $formRequest['cpf'] ?? null,
                        'senha' => $formRequest['senha'] ?? null,
                        'telefone' => $formRequest['telefone'] ?? null,
                        'endereco' => $formRequest['endereco'] ?? null,
                        'cargo' => $formRequest['cargo'] ?? null,
                        'genero' => $formRequest['genero'] ?? null,
                        'status' => $formRequest['status'] ?? null,
                        'img_profile' => $uploadFileName ?? null
                    ];

                    $this->gestor->setNome($dataWrite['nome']);
                    $this->gestor->setEmail($dataWrite['email']);
                    $this->gestor->setCpf($dataWrite['cpf']);
                    $this->gestor->setSenha($dataWrite['senha']);
                    $this->gestor->setTelefone($dataWrite['telefone']);
                    $this->gestor->setEndereco($dataWrite['endereco']);
                    $this->gestor->setCargo($dataWrite['cargo']);
                    $this->gestor->setGenero($dataWrite['genero']);
                    $this->gestor->setStatus($dataWrite['status']);
                    $this->gestor->setImgUrl($dataWrite['img_profile']);

                    if ($this->gestorDAO->register($this->gestor)) {
                        $this->container->get('flash')
                            ->addMessage('message.success', 'Usuário cadastrado com sucesso!');

                        $url = RouteContext::fromRequest($request)
                            ->getRouteParser()
                            ->urlFor('gestores.index');

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

        return $this->renderer->render($response, 'dashboard/gestores/register.php', $templateVariables);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $ID = $request->getAttribute('ID');
        $basePath = $this->container->get('settings')['api']['path'];
        $gestor = parent::getGestor();
        $uploadDirectory =
            $this->container->get('settings')['api']['uploadDirectory'] . '/img/profile';

        if ($gestor === []) {
            Session::destroy();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        // ID da URL
        $this->gestor->setID($ID);
        if ($this->gestorDAO->getGestorByID($this->gestor) === []) {
            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('gestores.index');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $gestorProfile = $this->gestorDAO->getGestorByID($this->gestor)[0];
        $authorize = self::authorize('update', $gestor, $gestorProfile);

        if ($request->getMethod() == 'POST') {
            // !important
            if ($ID !== Session::get('gestor.update.ID')) {
                $url = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('gestores.update', ['ID' => Session::get('gestor.update.ID')]);

                return $response
                    ->withHeader('Location', $url)
                    ->withStatus(302);
            }

            Session::delete('gestor.update.ID');

            $formRequest = (array)$request->getParsedBody();

            $regex = [
                'name' => '/^[A-Za-z .\'][^0-9,]+$/',
                'cargo' => '/^[1-2]{1}$/',
                'cpf' => '/^([0-9]){3}\.([0-9]){3}\.([0-9]){3}-([0-9]){2}$/',
                'telefone' => '/^\([0-9]{2}\) [0-9]?[0-9]{4}-[0-9]{4}$/',
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
                    'email' => true,
                    'unique-update' => 'email|gestor|' . $ID,
                    'max' => 128
                ],
                'cpf' => [
                    'label' => 'CPF',
                    'required' => true,
                    'cpf' => true,
                    'unique-update' => 'cpf|gestor|' . $ID,
                    'regex' => $regex['cpf']
                ],
                'telefone' => [
                    'label' => 'Telefone',
                    'required' => false,
                    'regex' => $regex['telefone']
                ],
                'endereco' => [
                    'label' => 'Endereço',
                    'required' => false,
                    'min' => 6,
                    'max' => 255
                ],
                'genero' => [
                    'label' => 'Gênero',
                    'required' => true,
                    'regex' => $regex['genero']
                ]
            ];

            if ($authorize['update']['cargo'])
                $rules['cargo'] = [
                    'cargo' => [
                        'label' => 'Cargo',
                        'required' => true,
                        'regex' => $regex['cargo']
                    ]
                ];

            if ($authorize['update']['status'])
                $rules['status'] = [
                    'label' => 'Status',
                    'required' => true,
                    'regex' => $regex['status']
                ];

            if (!empty($formRequest)) {
                $fields = [
                    'nome',
                    'email',
                    'cpf',
                    'telefone',
                    'endereco',
                    'genero'
                ];

                if ($authorize['update']['cargo']) $fields[] = 'cargo';
                if ($authorize['update']['status']) $fields[] = 'status';

                $persistUpdateValues = self::getPersistUpdateValues($gestorProfile, $formRequest);

                $this->validator->setFields($fields);
                $this->validator->setData($formRequest);
                $this->validator->setRules($rules);
                $this->validator->validation();

                if ($this->validator->passed()) {
                    $uploadedFiles = $request->getUploadedFiles();
                    $uploadedFile = $uploadedFiles['img_profile'] ?? [];

                    if (
                        ($uploadedFile !== []) &&
                        ($uploadedFile->getClientFilename() !== '')
                    ) {
                        $uploadRules = [
                            'mimeTypes' => [
                                'image/gif',
                                'image/jpeg',
                                'image/png'
                            ],
                            'maxSize' => 2097152 //2097152
                        ];

                        if (!self::validateMediaType($uploadedFile, $uploadRules))
                            $errors = (array)'O formato de arquivo para imagem de "Foto de Perfil" não é compatível.';

                        if (!self::validateFileSize($uploadedFile, $uploadRules))
                            $errors = (array)'O tamanho de arquivo para imagem de "Foto de Perfil" excede o permitido.';

                        if (
                            (self::validateMediaType($uploadedFile, $uploadRules)) &&
                            (self::validateFileSize($uploadedFile, $uploadRules))
                        ) {
                            $uploadedFile = $uploadedFile;
                            $uploadFileName = self::renameFile($uploadedFile);
                        }
                    }

                    $currentFileName = $gestorProfile['img_url'];
                    $uploadedFile = $uploadedFile ?? null;
                    $uploadFileName = $uploadFileName ?? null;

                    if (($uploadFileName !== null)) {
                        if (
                            ($currentFileName !== null) &&
                            ($currentFileName !== '')
                        ) {
                            $path = $uploadDirectory . '/' . $currentFileName;

                            if (file_exists($path)) unlink($path);
                        }

                        if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0777, true);

                        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                            self::moveUploadedFile($uploadDirectory, $uploadedFile, $uploadFileName);

                            $uploadStatus = true;
                        } else $errors = (array)'Houve um erro inesperado.';
                    }

                    $uploadStatus = $uploadStatus ?? false;

                    $dataRequest = [
                        'nome' => $formRequest['nome'] ?? null,
                        'email' => $formRequest['email'] ?? null,
                        'cpf' => $formRequest['cpf'] ?? null,
                        'telefone' => $formRequest['telefone'] ?? null,
                        'endereco' => $formRequest['endereco'] ?? null,
                        'genero' => $formRequest['genero'] ?? null
                    ];

                    if ($authorize['update']['cargo'])
                        $dataRequest['cargo'] = $formRequest['cargo'] ?? null;

                    if ($authorize['update']['status'])
                        $dataRequest['status'] = $formRequest['status'] ?? null;

                    if ($uploadStatus)
                        $dataRequest['img_profile'] = $uploadFileName ?? null;

                    $dataRequest['cargo'] = $dataRequest['cargo'] ?? $gestorProfile['cargo'];
                    $dataRequest['status'] = $dataRequest['status'] ?? $gestorProfile['status'];
                    $dataRequest['img_profile'] = $dataRequest['img_profile'] ?? $gestorProfile['img_url'];

                    $dataWrite = [
                        'nome' => $dataRequest['nome'] ?? null,
                        'email' => $dataRequest['email'] ?? null,
                        'cpf' => $dataRequest['cpf'] ?? null,
                        'telefone' => $dataRequest['telefone'] ?? null,
                        'endereco' => $dataRequest['endereco'] ?? null,
                        'cargo' => $dataRequest['cargo'] ?? null,
                        'genero' => $dataRequest['genero'] ?? null,
                        'status' => $dataRequest['status'] ?? null,
                        'img_profile' => $dataRequest['img_profile'] ?? null
                    ];

                    $this->gestor->setID($ID);
                    $this->gestor->setNome($dataWrite['nome']);
                    $this->gestor->setEmail($dataWrite['email']);
                    $this->gestor->setCpf($dataWrite['cpf']);
                    $this->gestor->setTelefone($dataWrite['telefone']);
                    $this->gestor->setEndereco($dataWrite['endereco']);
                    $this->gestor->setCargo($dataWrite['cargo']);
                    $this->gestor->setGenero($dataWrite['genero']);
                    $this->gestor->setStatus($dataWrite['status']);
                    $this->gestor->setImgUrl($dataWrite['img_profile']);

                    if ($this->gestorDAO->update($this->gestor)) {
                        $this->container->get('flash')
                            ->addMessage('message.success', 'Usuário atualizado com sucesso.');

                        $url = RouteContext::fromRequest($request)
                            ->getRouteParser()
                            ->urlFor('gestores.show', ['ID' => $ID]);

                        return $response
                            ->withHeader('Location', $url)
                            ->withStatus(302);
                    } else $errors = (array)'Houve um erro inesperado.';
                } else $errors = array_unique($this->validator->errors());
            }
        }

        Session::create('gestor.update.ID', $ID);

        $gestor = parent::applyGestorData($gestor);
        $persistUpdateValues = $persistUpdateValues ?? $gestorProfile;
        $gestorProfile = parent::applyGestorData($gestorProfile);
        $errors = $errors ?? [];

        if (
            !($authorize['update']['profile'])
        ) {
            $this->container->get('flash')
                ->addMessage('message.warning', 'Você não tem permissão para executar essa ação.');

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('gestores.index');

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

    private static function renameFile($uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $baseName = bin2hex(random_bytes(8));
        $fileName = $baseName . '.' . $extension;

        return $fileName;
    }

    private static function moveUploadedFile($uploadDirectory, $uploadedFile, $fileName)
    {
        $uploadedFile->moveTo($uploadDirectory . DIRECTORY_SEPARATOR . $fileName);

        return true;
    }

    private static function authorize($type, $gestor = [], $gestorProfile = [])
    {
        if (
            ($type == 'update') &&
            ($gestor !== []) &&
            ($gestorProfile !== [])
        ) {
            $status['active'] =  ($gestor['cargo'] == 1) ? 1 : 2;
            $status['profile'] = ($gestorProfile['cargo'] == 1) ? 1 : 2;

            $userAdminProfileAdmin = ($status['active'] == 1) && ($status['profile'] == 1) && ($gestor['ID'] != $gestorProfile['ID']);
            $userAdminProfileGestor = ($status['active'] == 1) && ($status['profile'] == 2) && ($gestor['ID'] != $gestorProfile['ID']);
            $userGestorOtherProfile = ($status['active'] == 2) && ($gestor['ID'] != $gestorProfile['ID']);
            $currentProfile = ($gestor['ID'] == $gestorProfile['ID']);

            if ($userAdminProfileAdmin) {
                $authorize['update']['profile'] = false;
                $authorize['update']['cargo'] = false;
                $authorize['update']['status'] = false;
            }

            if ($userAdminProfileGestor) {
                $authorize['update']['profile'] = true;
                $authorize['update']['cargo'] = true;
                $authorize['update']['status'] = true;
            }

            if ($userGestorOtherProfile) {
                $authorize['update']['profile'] = false;
                $authorize['update']['cargo'] = false;
                $authorize['update']['status'] = false;
            }

            if ($currentProfile) {
                $authorize['update']['profile'] = true;
                $authorize['update']['cargo'] = false;
                $authorize['update']['status'] = false;
            }

            return $authorize;
        }

        if (
            ($type == 'register') &&
            ($gestor !== [])
        ) {
            $status['active'] = ($gestor['cargo'] == 1) ? 1 : 2;

            if ($status['active'] == 1) return $authorize = ['register' => true];
            else return $authorize = ['register' => false];
        }
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
