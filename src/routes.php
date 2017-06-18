<?php
/**
 * Application Routes
 */

$app->get('/users', UsersController::class . ':index');
$app->post('/users', UsersController::class . ':create');
$app->get('/users/{id}', UsersController::class . ':show');
