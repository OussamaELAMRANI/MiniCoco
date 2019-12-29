<?php

namespace Coco\Modules;

use Coco;
use Coco\Router;

class CoolApp
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->router->get("/blog", function () {
            return 'Blog';
        }, 'blog.index');
    }
}