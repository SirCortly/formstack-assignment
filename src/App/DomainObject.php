<?php
namespace App;

use Exception;

abstract class DomainObject
{
    /**
     * @var int ID of Domain Object
     */
    protected $id = null;

    /**
     * Get ID of object (Unique to object type)
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set object ID
     *
     * @param int $id
     *
     * @throws Exception if the ID is already set.
     *
     * @return int
     */
    public function setId(int $id)
    {
        if ( ! is_null($this->id)) {
            throw new Exception('DomainObject ID is immutable');
        }
        $this->id = $id;
    }

    /**
     * Domain Object must define validation for itself
     *
     * return array Validation Errors
     */
    abstract public function validate() : array;
}
