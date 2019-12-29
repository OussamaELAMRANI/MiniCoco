<?php

namespace Coco;

use Coco\Middlewares\CallableMiddleware;
use Coco\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

/**
 * Is Proxy of @class FastRouteRouter
 * @class Router
 * @package Coco
 */
class Router
{

    /**
     * @var FastRouteRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * Add a New Route
     *
     * @param string $path
     * @param $middleware
     * @param string $name
     */
    public function get(string $path, $middleware, string $name)
    {
        $this->router->addRoute(new ZendRoute($path, new CallableMiddleware($middleware), ['GET'], $name));
    }

    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $route = $this->router->match($request);
        if ($route->isSuccess())
            return new Route(
                $route->getMatchedRouteName(),
                $route->getMatchedRoute()->getMiddleware()->getCallback(),
                $route->getMatchedParams()
            );
        return null;
    }

    /**
     * @param $name
     * @return string
     */
    public function generateUri($name): string
    {
        return $this->router->generateUri($name);
    }


}