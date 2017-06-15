<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class UsersTest extends TestCase
{
    protected $http;

    protected function setUp()
    {
        $this->http = new Client([
            'base_uri' => 'http://www.testbox.dev'
        ]);
    }

    protected function tearDown()
    {
        $this->http = null;
    }

    public function testGetUsers()
    {
        // GET /users
        $response = $this->http->get('/users');
        $data = json_decode($response->getBody(), true);

        $data_expectation = [
            'data' => [
                [
                    'email' => 'john.lennon@beatles.com',
                    'firstname' => 'John',
                    'lastname' => 'Lennon',
                ],
                [
                    'email' => 'george.harrison@beatles.com',
                    'firstname' => 'George',
                    'lastname' => 'Harrison',
                ],
                [
                    'email' => 'paul.mcartney@beatles.com',
                    'firstname' => 'Paul',
                    'lastname' => 'Mcartney',
                ],
                [
                    'email' => 'ringo.starr@beatles.com',
                    'firstname' => 'Ringo',
                    'lastname' => 'Starr',
                ]
            ]
        ];

        // Assert response 200 OK
        $this->assertEquals(200, $response->getStatusCode());

        // Assert Content-Type applciation/json
        $this->assertEquals(
            'application/json',
            $response->getHeaders()['Content-Type'][0]
        );

        $this->assertEquals($data_expectation, $data);
    }
}
