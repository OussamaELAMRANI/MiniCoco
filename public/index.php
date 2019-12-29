<?php

require_once "./../vendor/autoload.php";

use Coco\App;
use Coco\Middlewares\DispatcherMiddleware;
use Coco\Middlewares\NotFoundMiddleware;
use Coco\Middlewares\RouterMiddleware;
use Coco\Middlewares\UnslashMiddleware;
use Coco\Modules\CoolApp;
use GuzzleHttp\Psr7\ServerRequest;

#create a new App instance to bootstrap my App
$app = new App([
    CoolApp::class
]);

$app
    ->pipe(UnslashMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);

$res = $app->run(ServerRequest::fromGlobals());

Http\Response\send($res);