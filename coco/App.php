<?php

namespace Coco;

use Coco\Middlewares\CombineMiddleware;
use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ResponseInterface As Response, ServerRequestInterface as Request};
use function DI\create;

class App implements RequestHandlerInterface
{

    /**
     * @var array
     */
    private $middlewares;
    private $container;
    /**
     * @var array
     */
    private $modules;
    /**
     * App constructor.
     * @param array $modules
     * @throws Exception
     */
    public function __construct(array $modules = [])
    {
        $this->modules = $modules;
        $this->container = $this->getContainer();
    }

    public function run(Request $request): Response
    {
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }
        return $this->handle($request);

    }

    /**
     * @return ContainerInterface
     * @throws Exception
     */
    public function getContainer(): ContainerInterface
    {
        if (is_null($this->container)) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions([App::class => $this]);
            $builder->useAutowiring(true);
            $this->container = $builder->build();
        }
        return $this->container;
    }


    /**
     * Pipe the All middleware
     *
     * @param string $middleware
     * @return $this
     */
    public function pipe(string $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        $middleware = new CombineMiddleware($this->container, $this->middlewares, $this);
        return $middleware->handle($request);
    }

}