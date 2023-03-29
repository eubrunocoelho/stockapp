<?php

use Psr\{
    Http\Message\ResponseInterface,
    Http\Message\ServerRequestInterface
};

use Slim\{
    App
};

use App\Controller\{
    LoginController
};

return function (App $app) {
    $app->get('/', [LoginController::class, 'login']);
};
