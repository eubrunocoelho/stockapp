<?php

use Psr\{
    Http\Message\ResponseInterface,
    Http\Message\ServerRequestInterface
};

use Slim\{
    App,
    Routing\RouteCollectorProxy
};

use App\Controller\{
    LoginController,
    DashboardController
};

return function (App $app) {
    $app->group('', function (RouteCollectorProxy $group) {
        $group->get('/', [LoginController::class, 'login']);
        $group->get('/login', [LoginController::class, 'login'])->setName('login');
        $group->post('/login', [LoginController::class, 'login']);
    });

    $app->group('/dashboard', function (RouteCollectorProxy $group) {
        $group->get('', [DashboardController::class, 'index'])->setName('dashboard');
    });
};

// return function (App $app) {
//     $app->get('/', [LoginController::class, 'login']);
//     $app->get('/login', [LoginController::class, 'login'])->setName('login');
//     $app->post('/login', [LoginController::class, 'login']);
//     $app->get('/dashboard', [DashboardController::class, 'index'])->setName('dashboard');
// };