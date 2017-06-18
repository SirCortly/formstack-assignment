<?php
/**
 * Define Application Dependencies
 */

use App\Users\{
    UsersController,
    UserDataMapper,
    UserViewTransformer
};

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

    $user_mapper = new UserDataMapper($container->get('db'));
    $user_transformer = new UserViewTransformer();

    return new UsersController($user_mapper, $user_transformer);
};

