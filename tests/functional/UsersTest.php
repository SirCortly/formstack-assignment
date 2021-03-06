<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class UsersTest extends TestCase
{
    /**
     * @var GuzzleHttp\Cleint HTTP Client
     */
    protected $http;

    /**
    * @var PDO Databse connection
    */
    protected $pdo;

    /**
    * @var array Test Fixtures
    */
    private $fixtures = [
        [
            'email' => 'john.lennon@beatles.com',
            'password' => 'john',
            'firstname' => 'John',
            'lastname' => 'Lennon'
        ],
        [
            'email' => 'george.harrison@beatles.com',
            'password' => 'george',
            'firstname' => 'George',
            'lastname' => 'Harrison'
        ],
        [
            'email' => 'paul.mcartney@beatles.com',
            'password' => 'paul',
            'firstname' => 'Paul',
            'lastname' => 'Mcartney'
        ],
        [
            'email' => 'ringo.starr@beatles.com',
            'password' => 'ringo',
            'firstname' => 'Ringo',
            'lastname' => 'Starr'
        ]
    ];

    protected function setUp()
    {
        $this->http = new Client([
            'base_uri' => 'http://www.testbox.dev'
        ]);

        $this->pdo = new PDO('mysql:host=localhost;dbname=my_app', 'my_app', 'secret');

        // Truncate table
        $this->pdo->exec("TRUNCATE TABLE `users`;");

        // Load fixtures into DB
        foreach ($this->fixtures as $fixture) {
            $this->pdo->prepare("
                INSERT INTO `users` (
                    `email`, `password`, `firstname`, `lastname`, `created_at`
                )
                VALUES
                    (?, ?, ?, ?, '2017-06-18 00:00:00');
            ")->execute([
                $fixture['email'],
                $fixture['password'],
                $fixture['firstname'],
                $fixture['lastname']
            ]);
        }
    }

    protected function tearDown()
    {
        $this->http = null;

        $this->pdo->exec("TRUNCATE TABLE `users`;");
        $this->pdo = null;
    }

    /**
     * Test GET /users
     *
     * @coversNothing
     */
    public function testGetUsers()
    {
        // GET /users
        $response = $this->http->get('/users');

        // Assert response 200 OK
        $this->assertEquals(200, $response->getStatusCode());

        // Assert Content-Type applciation/json
        $this->assertEquals(
            'application/json;charset=utf-8',
            $response->getHeaders()['Content-Type'][0]
        );

        $data = json_decode($response->getBody(), true);
        $data_expectation = [
            [
                'id' => 1,
                'email' => 'john.lennon@beatles.com',
                'firstname' => 'John',
                'lastname' => 'Lennon',
                'fullname' => 'John Lennon',
                'created_at' => '2017-06-18 00:00:00',
                'updated_at' => null
            ],
            [
                'id' => 2,
                'email' => 'george.harrison@beatles.com',
                'firstname' => 'George',
                'lastname' => 'Harrison',
                'fullname' => 'George Harrison',
                'created_at' => '2017-06-18 00:00:00',
                'updated_at' => null
            ],
            [
                'id' => 3,
                'email' => 'paul.mcartney@beatles.com',
                'firstname' => 'Paul',
                'lastname' => 'Mcartney',
                'fullname' => 'Paul Mcartney',
                'created_at' => '2017-06-18 00:00:00',
                'updated_at' => null
            ],
            [
                'id' => 4,
                'email' => 'ringo.starr@beatles.com',
                'firstname' => 'Ringo',
                'lastname' => 'Starr',
                'fullname' => 'Ringo Starr',
                'created_at' => '2017-06-18 00:00:00',
                'updated_at' => null
            ]
        ];

        $this->assertEquals($data_expectation, $data);
    }

    /**
     * Test GET /users/{id}
     *
     * @coversNothing
     */
    public function testGetUsersById()
    {
        // GET /users/2
        $response = $this->http->get('/users/2');

        // Assert response 200 OK
        $this->assertEquals(200, $response->getStatusCode());

        // Assert Content-Type applciation/json
        $this->assertEquals(
            'application/json;charset=utf-8',
            $response->getHeaders()['Content-Type'][0]
        );

        $data = json_decode($response->getBody(), true);
        $data_expectation = [
            'id' => 2,
            'email' => 'george.harrison@beatles.com',
            'firstname' => 'George',
            'lastname' => 'Harrison',
            'fullname' => 'George Harrison',
            'created_at' => '2017-06-18 00:00:00',
            'updated_at' => null
        ];

        $this->assertEquals($data_expectation, $data);
    }

    /**
     * Test GET /users/{id} where {id} does not exist
     *
     * @coversNothing
     */
    public function testGetUsersByIdDoesNotExist()
    {
        // GET /users/2
        try {
            $this->http->get('/users/1234');
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();

            $this->assertEquals(404, $response->getStatusCode());

            $error = json_decode($response->getBody(), true);
            $error_expectation = [
                'message' => 'User not found'
            ];

            $this->assertEquals($error_expectation, $error);
        }
    }

    /**
     * Test POST /users
     *
     * @coversNothing
     */
    public function testPostUser()
    {
        $post_data = [
            'email' => 'kurt.cobain@nirvana.com',
            'password' => 'nirvana',
            'firstname' => 'Kurt',
            'lastname' => 'Cobain'
        ];

        // POST /users
        $response = $this->http->post('/users', ['json' => $post_data]);
        $data = json_decode($response->getBody(), true);

        // Assert response 201 Created
        $this->assertEquals(201, $response->getStatusCode());

        // Assert Content-Type applciation/json
        $this->assertEquals(
            'application/json;charset=utf-8',
            $response->getHeaders()['Content-Type'][0]
        );

        // Assert that created User was returned properly
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('kurt.cobain@nirvana.com', $data['email']);
        $this->assertFalse(array_key_exists('password', $data));
        $this->assertEquals('Kurt', $data['firstname']);
        $this->assertEquals('Cobain', $data['lastname']);
        $this->assertEquals('Kurt Cobain', $data['fullname']);
        $this->assertFalse(is_null($data['created_at']));
        $this->assertTrue(is_null($data['updated_at']));
    }

    /**
     * Test POSTing user with empty body returns error
     *
     * @coversNothing
     */
    public function testPostUserMissingParams()
    {
        try {
            $this->http->post('/users', []);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();

            $this->assertEquals(422, $response->getStatusCode());

            $error = json_decode($response->getBody(), true);
            $error_expectation = [
                'email' => 'Email is required',
                'password' => 'Password is required',
                'firstname' => 'Firstname is required',
                'lastname' => 'Lastname is required',
            ];

            $this->assertEquals($error_expectation, $error);
        }
    }

    /**
     * Test PUT /users/{id}
     *
     * @coversNothing
     */
    public function testPutUser()
    {
        $put_data = [
            'email' => 'kurt.cobain@nirvana.com'
        ];

        // PUT /users/1
        $put_response = $this->http->put('/users/1', ['json' => $put_data]);

        // Assert response 200 OK
        $this->assertEquals(200, $put_response->getStatusCode());

        // GET /users/1
        $get_response = $this->http->get('/users/1');
        $data = json_decode($get_response->getBody(), true);

        // Assert that User was updated
        $data = json_decode($get_response->getBody(), true);
        $this->assertEquals('kurt.cobain@nirvana.com', $data['email']);
    }

    /**
     * Test PUT /users/{id} with invalid email returns 422 response
     *
     * @coversNothing
     */
    public function testPutUserInvalidEmail()
    {
        $put_data = [
            'email' => 'notanemail'
        ];

        // PUT /users/1
        try {
            $this->http->put('/users/1', ['json' => $put_data]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();

            $this->assertEquals(422, $response->getStatusCode());

            $error = json_decode($response->getBody(), true);
            $error_expectation = [
                'email' => 'Email format is invalid',
            ];

            $this->assertEquals($error_expectation, $error);
        }
    }

    /**
     * Test DELETE /users/{id}
     *
     * @coversNothing
     */
    public function testDelete()
    {
        // DELETE /users/1
        $response = $this->http->delete('/users/1');
        $this->assertEquals(200, $response->getStatusCode());

        // GET /users/1 no longer exists
        try {
            $this->http->get('/users/1');
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();

            $this->assertEquals(404, $response->getStatusCode());

            $error = json_decode($response->getBody(), true);
            $error_expectation = [
                'message' => 'User not found'
            ];

            $this->assertEquals($error_expectation, $error);
        }
    }

    /**
     * Test DELETE /users/{id} not found returns 404
     *
     * @coversNothing
     */
    public function testDeleteNotFound()
    {
        // DELETE /users/123 Does not exist
        try {
            $this->http->delete('/users/123');
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();

            $this->assertEquals(404, $response->getStatusCode());

            $error = json_decode($response->getBody(), true);
            $error_expectation = [
                'message' => 'User not found'
            ];

            $this->assertEquals($error_expectation, $error);
        }
    }
}
