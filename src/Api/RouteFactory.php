<?php

namespace Famoser\Mamapp\Api;

use Famoser\Mamapp\Initializer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class RouteFactory
{
    /**
     * @param RouteCollectorProxy<ContainerInterface> $route
     */
    public static function addRoutes(RouteCollectorProxy $route): void
    {
        $route->get('/init', function (Request $request, Response $response) {
            RequestValidatorExtensions::checkApiKey($request);

            Initializer::init($response->getBody());

            return $response;
        });
    }
}
