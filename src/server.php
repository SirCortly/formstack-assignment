<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Load .env environment configuration file
(new \Dotenv\Dotenv(BASE_PATH))->load();

// Instantiate Slim App
$settings =  require(BASE_PATH . '/src/settings.php');
$app = new \Slim\App($settings);

// Register Routes
require BASE_PATH . '/src/routes.php';

// Run Application
$app->run();

