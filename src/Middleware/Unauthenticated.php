<?php

namespace App\Middleware;

use App\{
    Helper\Session
};

use Psr\{
    Http\Message\ServerRequestInterface as Request,
    Http\Server\RequestHandlerInterface as RequestHandler
};

use Slim\{
    Psr7\Response,
    Routing\RouteContext
};

class Unauthenticated
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (
            !Session::exists('gestorID') &&
            !Session::exists('authenticated')
        ) {
            $response = new Response();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $response = $handler->handle($request);
        return $response;
    }
}
