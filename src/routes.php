<?php
/**
 * Application Routes
 */

$app->get('/users', UsersController::class . ':index');
$app->get('/users/{id}', UsersController::class . ':show');
