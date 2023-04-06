<?php

use Slim\{
    App,
    Routing\RouteCollectorProxy
};

use App\Controller\{
    LoginController,
    DashboardController
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
        $group->get('', [DashboardController::class, 'index'])->setName('dashboard');
        $group->get('/logout', [DashboardController::class, 'logout'])->setName('logout');
    })->add(Unauthenticated::class);
};