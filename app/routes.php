<?php

use Slim\{
    App,
    Routing\RouteCollectorProxy
};

use App\Controller\{
    LoginController,
    DashboardController,
    GestoresController
};

use App\Middleware\{
    Authenticated,
    Unauthenticated
};

return function (App $app) {
    $app->group('', function (RouteCollectorProxy $group) {
        $group->get('/', [LoginController::class, 'login']);
        $group->get('/login', [LoginController::class, 'login'])->setName('login');
        $group->post('/login', [LoginController::class, 'login']);
    })->add(Authenticated::class);

    $app->group('/dashboard', function (RouteCollectorProxy $group) {
        $group->get('', [DashboardController::class, 'index'])->setName('dashboard.index');
        $group->get('/logout', [DashboardController::class, 'logout'])->setName('dashboard.logout');
    })->add(Unauthenticated::class);

    $app->group('/gestores', function (RouteCollectorProxy $group) {
        $group->get('/show/{ID}', [GestoresController::class, 'show'])->setName('gestores.show');
        $group->get('/update/{ID}', [GestoresController::class, 'update'])->setName('gestores.update');
        $group->post('/update/{ID}', [GestoresController::class, 'update']);
    })->add(Unauthenticated::class);
};
