<?php

namespace Coco;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\{ResponseInterface As Response, ServerRequestInterface as Request};

class App
{
    /**
     * @var ServerRequest
     */
    private $req;

    /**
     * App constructor.
     * @param array $modules
     */
    public function __construct(array $modules)
    {
    }

    public function run(Request $request): Response
    {
        $this->req = $request;
        return $this->deleteLastSlash();

    }

    private function deleteLastSlash(): Response
    {
        $uri = $this->req->getUri()->getPath();

        if (strlen($uri) > 1)
            if (!empty($uri) && $uri[-1] === '/') {
                return (new \GuzzleHttp\Psr7\Response())
                    ->withStatus(301)
                    ->withHeader('Location', substr($uri, 0, -1));
            }

        if ($uri == "/") {
            return new \GuzzleHttp\Psr7\Response(200, [], '<p>App</p>');
        } elseif ($uri == '/blog') {
            return new \GuzzleHttp\Psr7\Response(200, [], '<p>Blog</p>');
        } else {
            return new \GuzzleHttp\Psr7\Response(404, [], '<p>404</p>');
        }

    }

}