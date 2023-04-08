<?php

use Psr\{
    Container\ContainerInterface
};

use Slim\{
    App,
    Factory\AppFactory,
    Views\PhpRenderer
};

use App\{
    Validator\Validate
};

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    PhpRenderer::class => function (ContainerInterface $container) {
        return new PhpRenderer($container->get('settings')['views']['path']);
    },

    Validate::class => function (ContainerInterface $container) {
        return new Validate($container);
    },
    
    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['database'];

        $dsn = $settings['driver'] . ':host=' . $settings['host'] . ';port=' . $settings['port'] . ';dbname=' . $settings['database'];

        return new PDO($dsn, $settings['username'], $settings['password']);
    }
];
