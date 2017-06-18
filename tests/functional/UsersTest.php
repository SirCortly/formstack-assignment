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
}
