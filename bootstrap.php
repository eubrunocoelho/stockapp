<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Adiciona definições ao container
$containerBuilder->addDefinitions(__DIR__ . '/app/dependencies.php');

// Cria a instância do container
$container = $containerBuilder->build();

// Cria a instância do aplicativo Slim
$app = $container->get(App::class);

// Define o base path (pasta do projeto)
$app->setBasePath('/stockapp');

// Registra as rotas
(require __DIR__ . '/routes/web.php')($app);

// Registra os middlewares
(require __DIR__ . '/app/middleware.php')($app);

return $app;