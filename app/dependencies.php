<?php

use Psr\{
    Container\ContainerInterface
};

use Slim\{
    App,
    Factory\AppFactory,
    Views\PhpRenderer,
    Flash\Messages
};

use App\{
    Validator\Validator
};

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    'flash' => function () {
        $storage = [];

        return new Messages($storage);
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    PhpRenderer::class => function (ContainerInterface $container) {
        return new PhpRenderer($container->get('settings')['views']['path']);
    },

    Validator::class => function (ContainerInterface $container) {
        return new Validator($container);
    },

    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['database'];

        $dsn = $settings['driver'] . ':host=' . $settings['host'] . ';port=' . $settings['port'] . ';dbname=' . $settings['database'];

        return new PDO($dsn, $settings['username'], $settings['password']);
    }
];
