<?php


namespace Coco\Middlewares;


use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CombineMiddleware implements RequestHandlerInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var MiddlewareInterface
     */
    private $middleware;
    /**
     * @var RequestHandlerInterface
     */
    private $handler;
    /**
     * @var int
     */
    private $index = 0;


    /**
     * CombineMiddleware constructor.
     * @param ContainerInterface $container
     * @param array $middleware
     * @param RequestHandlerInterface $handler
     */
    public function __construct(ContainerInterface $container, array $middleware, RequestHandlerInterface $handler)
    {
        $this->container = $container;
        $this->middleware = $middleware;
        $this->handler = $handler;
    }


    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();

        if (is_null($middleware)) {
            return $this->handler->handle($request);
        } elseif (is_callable($middleware)) {
            $res = call_user_func_array($middleware, [$request, [$this, 'process']]);
            if (is_string($res)) {
                return new Response(200, [], $res);
            }
            return $res;
        } elseif ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }
    }

    /**
     * @return Middleware|callable|null
     */
    public function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middleware)) {
            if (is_string($this->middleware[$this->index])) {
                $middleware = $this->container->get($this->middleware[$this->index]);
            } else {
                $middleware = $this->middleware[$this->index];
            }
            $this->index++;
            return $middleware;
        }
        return null;
    }

}