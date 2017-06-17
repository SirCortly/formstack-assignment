<?php

use PHPUnit\Framework\TestCase;

use App\Users\{User, UserDataMapper};
use App\DataMapper;

class UserDataMapperTest extends TestCase
{
    /**
     * PDO Connection
     */
    private $pdo;

    /**
     * Initial number of rows in users table
     */
    private $num_fixtures;

    /**
     * Set up DB connection and add a couple records
     */
    protected function setUp()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=my_app', 'my_app', 'secret');

        // Setup some initial data
        $num_fixtures = $this->pdo->exec("
            INSERT INTO `users` (
                `email`, `password`, `firstname`, `lastname`, `created_at`
            )
            VALUES
                ('one@test.com', 'One', 'First', 'user', NOW()),
                ('two@test.com', 'Two', 'Second', 'user', NOW());
        ");
        $this->num_fixtures = $num_fixtures;
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

        // We should have num_fixtures + 1 records
        $this->assertCount($this->num_fixtures + 1, $result);

        // Id should corespond with num_fixtures
        $this->assertEquals($this->num_fixtures, $id);

        // Assert record came back with proper fields
        $record = $result[$this->num_fixtures];
        $this->assertEquals($record['email'], 'newUser@email.com');
        $this->assertEquals($record['password'], 'newuser');
        $this->assertEquals($record['firstname'], 'New');
        $this->assertEquals($record['lastname'], 'User');
        $this->assertTrue( ! is_null($record['created_at']));
        $this->assertNull($record['updated_at']);
    }
}
