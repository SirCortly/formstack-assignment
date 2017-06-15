<?php

use App\Users\UsersController;

$app->get('/users', UsersController::class . ':index');
