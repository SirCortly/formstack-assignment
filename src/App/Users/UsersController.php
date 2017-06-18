<?php
namespace App\Users;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\{
    AbstractDataMapper,
    AbstractViewTransformer
};

class UsersController
{
    /**
     * @var AbstractDataMapper User Data Mapper
     */
    protected $user_mapper;

    /**
     * @var AbstractViewTransformer User View Transformer
     */
    protected $user_transformer;

    /**
     * Constructor
     */
    public function __construct(
        AbstractDataMapper $user_mapper,
        AbstractViewTransformer $user_transformer
    )
    {
        $this->user_mapper = $user_mapper;
        $this->user_transformer = $user_transformer;
    }

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
        $users = $this->user_mapper->fetchAll();

        return $response->withJson(
            $this->user_transformer->transformCollection($users),
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

        try {
            $user = $this->user_mapper->fetchById($id);
        } catch(\Exception $e) {
            return $response->withJson(['message' => $e->getMessage()], 404);
        }

        return $response->withJson(
            $this->user_transformer->transform($user),
            200
        );
    }

    /**
     *  POST /users
     *
     * Create new User
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(Request $request, Response $response)
    {
        $user = $this->_populateUserFromRequestBody($request, new User());

        $validation_errors = $user->validate();
        if ($validation_errors) {
            return $response->withJson(
                $validation_errors,
                422
            );
        }

        $id = $this->user_mapper->save($user);

        return $response->withJson(
            $this->user_transformer->transform($this->user_mapper->fetchById($id)),
            201
        );
    }

    /**
     *  PUT /users/{id}
     *
     * Update User
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        try {
            $user = $this->user_mapper->fetchById($id);
        } catch(\Exception $e) {
            return $response->withJson(['message' => $e->getMessage()], 404);
        }

        // Update user based on request body
        $user = $this->_populateUserFromRequestBody($request, $user);

        $validation_errors = $user->validate();
        if ($validation_errors) {
            return $response->withJson(
                $validation_errors,
                422
            );
        }

        $this->user_mapper->save($user);

        return $response->withStatus(200);
    }

    /**
     *  DELETE /users/{id}
     *
     * Delete User
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        try {
            $user = $this->user_mapper->fetchById($id);
        } catch(\Exception $e) {
            return $response->withJson(['message' => $e->getMessage()], 404);
        }

        $this->user_mapper->delete($user);
        return $response->withStatus(200);
    }


    /**
     * Populate User based on request body
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param User $user
     */
    private function _populateUserFromRequestBody(Request $request, User $user)
    {
        $data = $request->getParsedBody();

        isset($data['email']) ? $user->setEmail($data['email']) : '';
        isset($data['password']) ? $user->setPassword($data['password']) : '';
        isset($data['firstname']) ? $user->setFirstname($data['firstname']) : '';
        isset($data['lastname']) ? $user->setLastname($data['lastname']) : '';

        return $user;
    }
}
