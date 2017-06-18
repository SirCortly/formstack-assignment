<?php

use PHPUnit\Framework\TestCase;
use App\Users\{
    User,
    UsersController,
    UserDataMapper,
    UserViewTransformer
};

class UsersControllerTest extends TestCase
{
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

        // Create Request and Response
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();

        // Mock DataMapper and ViewTransform
        $user_mapper = $this->createMock(UserDataMapper::class);
        $user_transformer = $this->createMock(UserViewTransformer::class);

        $controller = new UsersController($user_mapper, $user_transformer);

        // Expect $user_mapper->fetchAll() to be called once
        $user_mapper->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue([new User(), new User()]));

        // Mock result of transforming User collection
        $transformed_users = [
            ['email' => 'testone@email.com'],
            ['email' => 'testtwo@email.com']
        ];

        // Expect $user_transformer->transformCollection() to be called once
        $user_transformer->expects($this->once())
            ->method('transformCollection')
            ->will($this->returnValue($transformed_users));

        $response = $controller->index($request, $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($transformed_users, json_decode($response->getBody(), true));
    }
}
