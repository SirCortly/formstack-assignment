<?php

$app->get('/', function($request, $response) {
    $response->getBody()->write('Slim App');
    return $response;
});
