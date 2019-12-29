<?php


namespace Coco\Router;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Route implements RequestHandlerInterface
{

    private $name;
    private $middleware;
    private $params;

    public function __construct($name, $middleware, $params)
    {
        $this->name = $name;
        $this->middleware = $middleware;
        $this->params = $params;
    }

    /**
     * get Route Name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->middleware;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response();
    }
}