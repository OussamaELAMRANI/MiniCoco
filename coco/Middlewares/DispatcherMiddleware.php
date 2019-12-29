<?php


namespace Coco\Middlewares;


use Coco\Router\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DispatcherMiddleware extends Middleware
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(Route::class);

        // to Not Found
        if (is_null($route)) {
            return $handler->handle($request);
        }
        $callback = $route->getCallback();

        if (!is_array($callback)) {
            $callback = [$callback];
        }
        return (new CombineMiddleware($this->container, $callback, $handler))->handle($request);
    }
}