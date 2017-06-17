<?php

use PHPUnit\Framework\TestCase;

use App\Users\{User, UserDataMapper};
use App\DataMapper;

class UserDataMapperTest extends TestCase
{
    /**
     * @var PDO PDO Connection
     */
    private $pdo;

    /**
     * @var array Test Fixtures
     */
    private $fixtures = [
        [
            'email' => 'one@test.com',
            'password' => 'one',
            'firstname' => 'First',
            'lastname' => 'User'
        ],
        [
            'email' => 'two@test.com',
            'password' => 'two',
            'firstname' => 'Second',
            'lastname' => 'User'
        ]
    ];

    /**
     * Set up DB connection and add a couple records
     */
    protected function setUp()
    {
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
                    (?, ?, ?, ?, NOW());
            ")->execute([
                $fixture['email'],
                $fixture['password'],
                $fixture['firstname'],
                $fixture['lastname'],
            ]);
        }
    }

    /**
     * Clean up DB
     */
    protected function tearDown()
    {
        // Truncate table
        $this->pdo->exec("TRUNCATE TABLE `users`;");
    }

    /**
     * Test get all Users
     */
    public function testFetchAll()
    {
        $mapper = new UserDataMapper($this->pdo);

        $users = $mapper->fetchAll();

        // We should recieve a User for each of our fixtures
        $this->assertCount(sizeof($this->fixtures), $users);

        // Verify that each User matches fixture
        foreach ($users as $index => $user) {
            $this->assertInstanceOf(User::class, $user);

            $this->assertEquals($index + 1, $user->getId());
            $this->assertEquals($this->fixtures[$index]['email'], $user->getEmail());
            $this->assertEquals($this->fixtures[$index]['password'], $user->getPassword());
            $this->assertEquals($this->fixtures[$index]['firstname'], $user->getFirstname());
            $this->assertEquals($this->fixtures[$index]['lastname'], $user->getLastname());
        }
    }

    /**
     * Test get User by ID
     */
    public function testFetchById()
    {
        $mapper = new UserDataMapper($this->pdo);

        // Fetch first test fixture
        $user = $mapper->fetchById(1);

        // Assert User fields reflect fixture data
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals($this->fixtures[0]['email'], $user->getEmail());
        $this->assertEquals($this->fixtures[0]['password'], $user->getPassword());
        $this->assertEquals($this->fixtures[0]['firstname'], $user->getFirstname());
        $this->assertEquals($this->fixtures[0]['lastname'], $user->getLastname());
    }

    /**
     * Test that exception is thrown if we try to fetch a
     * User by ID that doesn't exist.
     */
    public function testFetchByIdNotFoundThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User not found');

        $mapper = new UserDataMapper($this->pdo);

        $user = $mapper->fetchById(123);
    }

    /**
     * Test create new User
     */
    public function testCreate()
    {
        $user = new User();
        $mapper = new UserDataMapper($this->pdo);

        // Initialize User
        $user->setEmail('newUser@email.com');
        $user->setPassword('newuser');
        $user->setFirstname('New');
        $user->setLastname('User');

        // Persist new User
        $id = $mapper->save($user);

        // Fetch result
        $result = $this->pdo->query("
            SELECT *
            FROM `users`
        ")->fetchAll(PDO::FETCH_ASSOC);

        // We should have fixtures + 1 records
        $this->assertCount(sizeof($this->fixtures) + 1, $result);

        // Id should corespond with number of fixtures + 1
        $this->assertEquals(sizeof($this->fixtures) + 1, $id);

        // Assert record came back with proper fields
        $record = $result[sizeof($this->fixtures)];
        $this->assertEquals($record['email'], 'newUser@email.com');
        $this->assertEquals($record['password'], 'newuser');
        $this->assertEquals($record['firstname'], 'New');
        $this->assertEquals($record['lastname'], 'User');
        $this->assertTrue( ! is_null($record['created_at']));
        $this->assertNull($record['updated_at']);
    }

    /**
     * Test update existing User
     */
    public function testUpdate()
    {
        $mapper = new UserDataMapper($this->pdo);

        $user = $mapper->fetchById(1);

        $user->setEmail('new@email.com');
        $user->setPassword('newpass');
        $user->setFirstname('New');
        $user->setLastname('Name');

        $id = $mapper->save($user);

        $row = $this->pdo->query("
            SELECT * FROM users WHERE id = 1
        ")->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(1, $row['id']);
        $this->assertEquals('new@email.com', $row['email']);
        $this->assertEquals('newpass', $row['password']);
        $this->assertEquals('New', $row['firstname']);
        $this->assertEquals('Name', $row['lastname']);
        $this->assertTrue( ! is_null($record['created_at']));
        $this->assertTrue( ! is_null($record['updated_at']));
    }
}
