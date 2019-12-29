<?php


namespace Test\Features;


use Coco\App;
use Coco\Middlewares\UnslashMiddleware;
use Coco\Modules\CoolApp;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    public function testRedirectPermanently()
    {
        $uri = '/aaa/';
        $req = new ServerRequest('GET', $uri);
        $app = New App([]);
        $app->pipe(UnslashMiddleware::class);
        $res = $app->run($req);
        $this->assertContains('/aaa', $res->getHeader('Location'));
        $this->assertEquals(301, $res->getStatusCode());
    }

    public function testGetBlog()
    {
        $uri = '/blog';
        $app = new App([
            CoolApp::class
        ]);
        $req = new ServerRequest('GET', $uri);
        $app->pipe(\Coco\Middlewares\UnslashMiddleware::class)
        ->pipe(\Coco\Middlewares\RouterMiddleware::class)
        ->pipe(\Coco\Middlewares\DispatcherMiddleware::class)
        ->pipe(\Coco\Middlewares\NotFoundMiddleware::class);
        $res = $app->run($req);
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertEquals("Blog", $res->getBody());
    }

    public function testNotFoundError()
    {
        $uri = '/coco';
        $app = new App([]);
        $req = new ServerRequest('GET', $uri);
        $app->pipe(\Coco\Middlewares\UnslashMiddleware::class)
            ->pipe(\Coco\Middlewares\RouterMiddleware::class)
            ->pipe(\Coco\Middlewares\DispatcherMiddleware::class)
            ->pipe(\Coco\Middlewares\NotFoundMiddleware::class);
        $res = $app->run($req);
        $this->assertEquals(404, $res->getStatusCode());
        $this->assertEquals('Not Found', $res->getBody());
    }
}