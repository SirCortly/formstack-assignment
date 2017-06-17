<?php
namespace App\Users;

use App\DomainObject;

class User extends DomainObject
{
    /**
     * @var string Email Address
     */
    protected $email;

    /**
     * @var string Password
     */
    protected $password;

    /**
     * @var string User's first name
     */
    protected $firstname;

    /**
     * @var string User's last name
     */
    protected $lastname;

    /**
     * Get Email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set Email
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Get Password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Password
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get Firstname
     *
     * @return string $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set Firstname
     *
     * @param string $firstname
     *
     * @return void
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get Lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set Lastname
     *
     * @param string $lastname
     *
     * @return void
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /*
     * Get User's Firstname + Lastname
     *
     * @return string
     */
    public function getFullName() : string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
