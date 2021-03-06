<?php

use PHPUnit\Framework\TestCase;
use App\Users\User;

class UserTest extends TestCase
{
    /**
     * Test $email setters and getters
     *
     * @covers \App\Users\User::setEmail
     * @covers \App\Users\User::getEmail
     */
    public function testSetGetEmail()
    {
        $user = new User();

        $email = 'kurt@russell.com';
        $user->setEmail($email);

        $this->assertEquals($email, $user->getEmail());
    }

    /**
     * Test $password setters and getters
     *
     * @covers \App\Users\User::setPassword
     * @covers \App\Users\User::getPassword
     */
    public function testSetGetPassword()
    {
        $user = new User();

        $password = 'wherewereyouchilds';
        $user->setPassword($password);

        $this->assertEquals($password, $user->getPassword());
    }

    /**
     * Test $firstname setters and getters
     *
     * @covers \App\Users\User::setFirstname
     * @covers \App\Users\User::getFirstname
     */
    public function testSetGetFirstname()
    {
        $user = new User();

        $firstname = 'Kurt';
        $user->setFirstname($firstname);

        $this->assertEquals($firstname, $user->getFirstname());
    }

    /**
     * Test $lastname setters and getters
     *
     * @covers \App\Users\User::setLastname
     * @covers \App\Users\User::getLastname
     */
    public function testSetGetlastname()
    {
        $user = new User();

        $lastname = 'Russell';
        $user->setLastname($lastname);

        $this->assertEquals($lastname, $user->getLastname());
    }

    /**
     * Test $user->getFullName() returns "Firstname Lastname"
     *
     * @covers \App\Users\User::getFullName
     */
    public function testGetFullName()
    {
        $user = new User();
        $user->setFirstname('Kurt');
        $user->setLastname('Russell');

        $full_name = $user->getFullName();
        $this->assertEquals('Kurt Russell', $full_name);
    }

    /**
     * Test that validation returns empty array on valid User
     *
     * @covers \App\Users\User::validate
     */
    public function testValidateSuccess()
    {
        $user = new User();
        $user->setEmail('kurt@russell.com');
        $user->setPassword('wherewereyouchilds');
        $user->setFirstname('Kurt');
        $user->setLastname('Russell');

        $validation_errors = $user->validate();

        $this->assertEquals([], $validation_errors);
    }

    /**
     * Test that validation returns errors on invalid email
     *
     * @covers \App\Users\User::validate
     */
    public function testValidateInvalidEmail()
    {
        $user = new User();
        $user->setEmail('kurtrussell.com');
        $user->setPassword('wherewereyouchilds');
        $user->setFirstname('Kurt');
        $user->setLastname('Russell');

        $validation_errors = $user->validate();

        $this->assertEquals([
            'email' => 'Email format is invalid'
        ], $validation_errors);
    }

    /**
     * Test that validation returns errors when required fields are missing
     *
     * @covers \App\Users\User::validate
     */
    public function testValidateRequiredFields()
    {
        $user = new User();

        $validation_errors = $user->validate();

        $this->assertEquals([
            'email' => 'Email is required',
            'password' => 'Password is required',
            'firstname' => 'Firstname is required',
            'lastname' => 'Lastname is required'
        ], $validation_errors);
    }
}
