<?php
namespace App\Users;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\AbstractController;

class UsersController extends AbstractController
{
    /**
     * GET /users
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

    /**
     *  GET /users{id}
     *
     * Return a single User by ID
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function show(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $mapper = new UserDataMapper($this->container->get('db'));

        try {
            $user = $mapper->fetchById($id);
        } catch(\Exception $e) {
            return $response->withJson(['message' => $e->getMessage()], 404);
        }

        $transformer = new UserViewTransformer();

        return $response->withJson(
            $transformer->transform($user),
            200
        );
    }
}
