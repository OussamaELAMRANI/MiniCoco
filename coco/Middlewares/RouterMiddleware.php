<?php


namespace Coco\Middlewares;


use Coco\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouterMiddleware extends Middleware
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }


    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->match($request);

        if (is_null($route)) {
            return $handler->handle($request);
        }

        $params = $route->getParams();
        $request = array_reduce(array_keys($params), function ($req, $key) use ($params) {
            return $req->withAttribute($key, $params[$key]);
        }, $request);

        $request = $request->withAttribute(get_class($route), $route);
        return $handler->handle($request);
    }
}