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
    $app->get('/login', [LoginController::class, 'login']);
    $app->post('/login', [LoginController::class, 'login']);
};
