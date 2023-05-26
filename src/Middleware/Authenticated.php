<?php

namespace App\Middleware;

use Psr\{
    Http\Message\ServerRequestInterface as Request,
    Http\Server\RequestHandlerInterface as RequestHandler
};

use Slim\{
    Psr7\Response,
    Routing\RouteContext
};

use App\{
    Helper\Session
};

class Authenticated
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (
            (Session::exists('authenticated')) &&
            (Session::exists('gestorID'))
        ) {
            if (
                Session::get('authenticated')
            ) {
                $response = new Response();

                $url = RouteContext::fromRequest($request)
                    ->getRouteParser()
                    ->urlFor('dashboard.index');

                return $response
                    ->withHeader('Location', $url)
                    ->withStatus(302);
            }
        }

        $response = $handler->handle($request);
        return $response;
    }
}
