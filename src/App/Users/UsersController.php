<?php
namespace App\Users;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\AbstractController;

class UsersController extends AbstractController
{
    /**
     * Index method for GET /users
     *
     * Return a list of Users
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request, Response $response)
    {
        $mapper = new UserDataMapper($this->container->get('db'));
        $users = $mapper->fetchAll();

        $transformer = new UserViewTransformer();

        return $response->withJson(
            $transformer->transformCollection($users),
            200
        );
    }
}
