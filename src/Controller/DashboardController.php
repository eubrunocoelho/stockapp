<?php

namespace App\Controller;

use Psr\{
    Http\Message\ResponseInterface as Response,
    Http\Message\ServerRequestInterface as Request
};

use Slim\{
    App,
    Routing\RouteContext,
    Views\PhpRenderer
};

use App\{
    Helper\Session
};

class DashboardController extends BaseController
{
    private
        $app, $container, $renderer;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->renderer = $this->container->get(PhpRenderer::class);

        parent::__construct($this->app);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $basePath = $this->container->get('settings')['api']['path'];

        $templateVariables = [
            'basePath' => $basePath
        ];

        return $this->renderer->render($response, 'dashboard/index.php', $templateVariables);
    }

    public function logout(Request $request, Response $response, array $args): Response
    {
        Session::destroy();

        $url = RouteContext::fromRequest($request)
            ->getRouteParser()
            ->urlFor('login');

        return $response
            ->withHeader('Location', $url)
            ->withStatus(302);
    }
}
