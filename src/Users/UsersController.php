<?php
namespace App\Users;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UsersController
{
    public function __construct()
    {
    }

    public function index(Request $request, Response $response, $args)
    {
        return $response->withJson([
            'data' => 'test'
        ]);
    }
}
