<?php


namespace Test;


use Coco\App;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    public function testRedirectPermanently()
    {
        $uri = '/aaa/';
        $req = new ServerRequest('GET', $uri);
        $app = New App([]);
        $res = $app->run($req);
        $this->assertContains('/aaa', $res->getHeader('Location'));
        $this->assertEquals(301, $res->getStatusCode());
    }

    public function testGetBlog()
    {
        $uri = '/blog';
        $app = new App([]);
        $req = new ServerRequest('GET', $uri);
        $res = $app->run($req);
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertEquals("<p>Blog</p>", $res->getBody());
    }

    public function testNotFoundError()
    {
        $uri = '/coco';
        $app = new App([]);
        $req = new ServerRequest('GET', $uri);
        $res = $app->run($req);
        $this->assertEquals(404, $res->getStatusCode());
        $this->assertEquals("<p>404</p>", $res->getBody());
    }
}