<?php

namespace App\Middleware;

use Slim\{
    App
};

class FlashMessage
{
    private
        $app, $container;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
    }

    public function __invoke($request, $next)
    {
        $this->container->get('flash')->__construct($_SESSION);

        return $next->handle($request);
    }
}
