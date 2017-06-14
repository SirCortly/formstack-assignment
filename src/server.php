<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Load .env environment configuration file
(new \Dotenv\Dotenv(BASE_PATH))->load();

// Instantiate Slim App
$settings = require './src/settings.php';
$app = new \Slim\App($settings);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Slim Application');
    return $response;
});
$app->run();

