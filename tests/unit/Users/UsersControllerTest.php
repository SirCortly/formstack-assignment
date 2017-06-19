<?php

use Slim\Http\{Environment, RequestBody, Request, Response, Uri, Headers, UploadedFile};
use PHPUnit\Framework\TestCase;
use App\Users\{
    User,
    UsersController,
    UserDataMapper,
    UserViewTransformer
};

class UsersControllerTest extends TestCase
{
    /*
     * @var UserDataMapper
     */
    private $user_mapper;

    /*
     * @var UserViewTransformer
     */
    private $user_transformer;

    /*
     * @var UsersController
     */
    private $controller;

    protected function setUp()
    {
        // Mock DataMapper and ViewTransform
        $this->user_mapper = $this->createMock(UserDataMapper::class);
        $this->user_transformer = $this->createMock(UserViewTransformer::class);

        $this->controller = new UsersController($this->user_mapper, $this->user_transformer);
    }

    protected function tearDown()
    {
        $this->user_mapper = null;
        $this->user_transformer = null;
        $this->controller = null;
    }

    /**
    * Test index method
    *
    * @covers \App\Users\UsersController::index
     */
    public function testIndex()
    {
        // Mock environment
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/users'
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();

        // Expect $user_mapper->fetchAll() to be called once
        $this->user_mapper->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue([new User(), new User()]));

        // Mock result of transforming User collection
        $transformed_users = [
            ['email' => 'testone@email.com'],
            ['email' => 'testtwo@email.com']
        ];

        // Expect $user_transformer->transformCollection() to be called once
        $this->user_transformer->expects($this->once())
            ->method('transformCollection')
            ->will($this->returnValue($transformed_users));

        $response = $this->controller->index($request, $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($transformed_users, json_decode($response->getBody(), true));
    }

    /**
     * Test show method
     *
    * @covers \App\Users\UsersController::show
     */
    public function testShow()
    {
        // Mock environment
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/users/101'
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $args = ['id' => 101];

        $user = new User();
        $user->setId(101);

        // Expect $user_mapper->fetchById() to be called once
        $this->user_mapper->expects($this->once())
            ->method('fetchById')
            ->with($this->equalTo(101))
            ->will($this->returnValue($user));

        // Mock result of transforming User object
        $transformed_user = ['id' => 101];

        // Expect $user_transformer->transform() to be called once
        $this->user_transformer->expects($this->once())
            ->method('transform')
            ->with($this->equalTo($user))
            ->will($this->returnValue($transformed_user));

        $response = $this->controller->show($request, $response, $args);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($transformed_user, json_decode($response->getBody(), true));
    }

    /**
     * Test show where ID is not found
     *
    * @covers \App\Users\UsersController::show
    * @covers \App\Users\UsersController::_respondUserNotFound
     */
    public function testShowUserNotFound()
    {
        // Mock environment
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/users/101'
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $args = ['id' => 101];

        // Expect $user_mapper->fetchById() to be called once and throw exception
        $this->user_mapper->expects($this->once())
            ->method('fetchById')
            ->with($this->equalTo(101))
            ->will($this->throwException(new \Exception));

        $response = $this->controller->show($request, $response, $args);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['message' => 'User not found'], json_decode($response->getBody(), true));
    }

    /**
     * Test create method
     *
    * @covers \App\Users\UsersController::create
     */
    public function testCreate()
    {
        // Mock Environment
        $env = Environment::mock();
        $uri = Uri::createFromString('/users');
        $headers = Headers::createFromEnvironment($env);
        $serverParams = $env->all();
        $uploadedFiles = UploadedFile::createFromEnvironment($env);
        $body = new RequestBody();

        $request = new Request('POST', $uri, $headers, [], $serverParams, $body, $uploadedFiles);
        $request->getBody()->write(json_encode([
            'email' => 'test@email.com',
            'password' => 'test',
            'firstname' => 'New',
            'lastname' => 'User',
        ]));
        $request->getBody()->rewind();

        $response = new Response();

        $user = new User();
        $user->setEmail('test@email.com');
        $user->setPassword('test');
        $user->setFirstname('New');
        $user->setLastname('User');

        // Expect $user_mapper->save() to be called once with $user
        $this->user_mapper->expects($this->once())
            ->method('save')
            ->with($this->equalTo($user))
            ->will($this->returnValue(101));

        // Expect $user_mapper->fetchById() to be called once
        $this->user_mapper->expects($this->once())
            ->method('fetchById')
            ->with($this->equalTo(101))
            ->will($this->returnValue($user));

        $transformed_user = ['id' => 101];

        // Expect $user_transformer->transform() to be called once
        $this->user_transformer->expects($this->once())
            ->method('transform')
            ->with($this->equalTo($user))
            ->will($this->returnValue($transformed_user));

        $response = $this->controller->create($request, $response);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($transformed_user, json_decode($response->getBody(), true));
    }

    /**
     * Test create with invalid input
     *
    * @covers \App\Users\UsersController::create
     */
    public function testCreateValidationErrors()
    {
        // Mock Environment
        $env = Environment::mock();
        $uri = Uri::createFromString('/users');
        $headers = Headers::createFromEnvironment($env);
        $serverParams = $env->all();
        $uploadedFiles = UploadedFile::createFromEnvironment($env);
        $body = new RequestBody();

        $request = new Request('POST', $uri, $headers, [], $serverParams, $body, $uploadedFiles);
        $response = new Response();

        $response = $this->controller->create($request, $response);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * Test delete method
     *
     * @covers \App\Users\UsersController::delete
     */
    public function testDelete()
    {
        // Mock environment
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/users/101'
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $args = ['id' => 101];

        $user = new User();
        $user->setId(101);

        // Expect $user_mapper->fetchById() to be called once
        $this->user_mapper->expects($this->once())
            ->method('fetchById')
            ->with($this->equalTo(101))
            ->will($this->returnValue($user));

        // Expect $user_mapper->delete() to be called once
        $this->user_mapper->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($user));

        $response = $this->controller->delete($request, $response, $args);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test delete where ID is not found
     *
    * @covers \App\Users\UsersController::delete
    * @covers \App\Users\UsersController::show
     */
    public function testdeleteUserNotFound()
    {
        // Mock environment
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/users/101'
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $args = ['id' => 101];

        // Expect $user_mapper->fetchById() to be called once and throw exception
        $this->user_mapper->expects($this->once())
            ->method('fetchById')
            ->with($this->equalTo(101))
            ->will($this->throwException(new \Exception));

        $response = $this->controller->delete($request, $response, $args);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['message' => 'User not found'], json_decode($response->getBody(), true));
    }
}
