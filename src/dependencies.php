<?php
/**
 * Define Application Dependencies
 */

$container = $app->getContainer();

/**
 * Inject databse into container
 */
$container['db'] = function ($container) {
    $settings = $container->get('settings')['db'];

    $host = $settings['host'];
    $name = $settings['name'];
    $user = $settings['user'];
    $pass = $settings['pass'];

    return new \PDO("mysql:host=$host;dbname=$name", $user, $pass);
};

/**
 * Inject container into controller constructor
 */
$container['UsersController'] = function($container) {
    return new \App\Users\UsersController($container);
};

