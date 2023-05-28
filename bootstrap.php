<?php

session_start();

use DI\{
    ContainerBuilder
};

use Slim\{
    App
};

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/functions.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/app/dependencies.php');

$container = $containerBuilder->build();

$app = $container->get(App::class);
$app->setBasePath('/' . $container->get('settings')['api']['path']);

(require __DIR__ . '/app/routes.php')($app);
(require __DIR__ . '/app/middleware.php')($app);

return $app;
