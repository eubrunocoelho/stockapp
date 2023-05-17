<?php

use Slim\{
    App,
    Routing\RouteCollectorProxy
};

use App\Controller\{
    LoginController,
    DashboardController,
    EntradaSaidaController,
    GestoresController,
    LivrosController,
};

use App\Middleware\{
    Authenticated,
    Unauthenticated,
    FlashMessage
};

return function (App $app) {
    $app->add(FlashMessage::class);

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
        $group->get('', [GestoresController::class, 'index'])->setName('gestores.index');
        $group->get('/show/{ID}', [GestoresController::class, 'show'])->setName('gestores.show');
        $group->get('/register', [GestoresController::class, 'register'])->setName('gestores.register');
        $group->post('/register', [GestoresController::class, 'register']);
        $group->get('/update/{ID}', [GestoresController::class, 'update'])->setName('gestores.update');
        $group->post('/update/{ID}', [GestoresController::class, 'update']);
    })->add(Unauthenticated::class);

    $app->group('/livros', function (RouteCollectorProxy $group) {
        $group->get('', [LivrosController::class, 'index'])->setName('livros.index');
        $group->get('/show/{ID}', [LivrosController::class, 'show'])->setName('livros.show');
        $group->get('/register', [LivrosController::class, 'register'])->setName('livros.register');
        $group->post('/register', [LivrosController::class, 'register']);
        $group->get('/update/{ID}', [LivrosController::class, 'update'])->setName('livros.update');
        $group->post('/update/{ID}', [LivrosController::class, 'update']);
        $group->get('/entrada/{ID}', [EntradaSaidaController::class, 'entrada'])->setName('livros.entrada');
        $group->post('/entrada/{ID}', [EntradaSaidaController::class, 'entrada']);
    })->add(Unauthenticated::class);
};
