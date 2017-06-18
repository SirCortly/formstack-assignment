<?php
/**
 * Application Routes
 */

$app->get('/users', UsersController::class . ':index');
$app->get('/users/{id}', UsersController::class . ':show');
$app->post('/users', UsersController::class . ':create');
$app->put('/users/{id}', UsersController::class . ':update');
