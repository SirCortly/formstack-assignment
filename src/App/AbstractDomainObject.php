<?php
namespace App;

use Exception;

abstract class AbstractDomainObject
{
    /**
     * @var int ID of Domain Object
     */
    protected $id = null;

    /**
     * @var DateTime When object was created
     */
    protected $created_at = null;

    /**
     * @var DateTime When object was last updated
     */
    protected $updated_at = null;

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
     * Get Created At
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set Created At
     *
     * @param DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * Get Updated At
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set Updated At
     *
     * @param DateTime $updated_at
     */
    public function setUpdatedAt(\DateTime $updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * Domain Object must define validation for itself
     *
     * return array Validation Errors
     */
    abstract public function validate() : array;
}
