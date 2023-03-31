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
    Validator\Validator
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

    Validator::class => function (ContainerInterface $container) {
        return new Validator($container);
    }
];
