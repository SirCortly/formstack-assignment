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
     * Determine whether or not user is in a valid state
     *
     * @return array Validation Errors
     */
    public function validate() : array
    {
        $errors = [];

        // Valiadate Email
        if ( ! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            if (is_null($this->email)) {
                $errors['email'] = 'Email is required';
            }
            else {
                $errors['email'] = 'Email format is invalid';
            }
        }

        // Validate Password
        if (is_null($this->password)) {
            $errors['password'] = 'Password is required';
        }

        // Validate Firstname
        if (is_null($this->firstname)) {
            $errors['firstname'] = 'Firstname is required';
        }

        // Validate Lastname
        if (is_null($this->lastname)) {
            $errors['lastname'] = 'Lastname is required';
        }

        return $errors;
    }

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
