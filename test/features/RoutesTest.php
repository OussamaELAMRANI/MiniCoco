<?php


namespace Test\Features;


use Coco\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    /**
     * @var Router
     */
    private $routes;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routes = new Router();
    }

    public function testGetRouteNameAndCallback()
    {
        $uri = '/blog';
        $req = new ServerRequest('GET', $uri);

        $this->routes->get($uri, function () {
            return 'blog';
        }, 'blog.index');

        $route = $this->routes->match($req);

        $this->assertEquals('blog.index', $route->getName());
        $this->assertEquals('blog', call_user_func($route->getCallback(), [$req]));
    }

    public function testGenerateUri()
    {
        $uri = '/blog';
        $name = 'blog.index';
        $this->routes->get($uri, function () {
            return 'blog';
        }, $name);

        $this->assertEquals($uri, $this->routes->generateUri($name));
    }

}