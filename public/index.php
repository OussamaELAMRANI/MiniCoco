<?php

require_once "./../vendor/autoload.php";

use Coco\App;
use GuzzleHttp\Psr7\ServerRequest;


#create a new App instance to bootstrap my App
$app = new App([

]);


$res = $app->run(ServerRequest::fromGlobals());

Http\Response\send($res);