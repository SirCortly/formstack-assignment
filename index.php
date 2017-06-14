<?php

// Require Composer Autoload
require './vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

// Instantiate Slim App
$app = new \Slim\App($config);
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Slim Application');
    return $response;
});
$app->run();

