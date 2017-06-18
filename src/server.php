<?php
/**
 * Server Initialization
 */

// Load .env environment configuration file
(new \Dotenv\Dotenv(BASE_PATH))->load();

// Instantiate Slim App
$settings =  require(BASE_PATH . '/src/settings.php');
$app = new \Slim\App($settings);

// Register Dependencies
require BASE_PATH . '/src/dependencies.php';

// Register Routes
require BASE_PATH . '/src/routes.php';

// Run Application
$app->run();

