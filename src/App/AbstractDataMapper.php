<?php
namespace App;

abstract class AbstractDataMapper
{
    /**
     * PDO connection
     */
    protected $db;

    /**
     * Constructor
     *
     * @param PDO $db PDO connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Persist AbstractDomainObject to storage
     *
     * Determine whether an object should be created or updated
     * and act accordingly.
     *
     * @param AbstractDomainObject $entity Object to persist
     *
     * @return int ID of persisted object
     *
     */
    public function save(AbstractDomainObject $entity) : int
    {
        if (is_null($entity->getId())) {
            return $this->_create($entity);
        }
        return $this->_update($entity);
    }

    /**
     * Get all instances of AbstractDomainObject type
     *
     * @return AbstractDomainObject[]
     */
    abstract public function fetchAll() : array;

    /**
     * Get a specific instance of AbstractDomainObject by ID
     *
     * @return AbstractDomainObject
     */
    abstract public function fetchById(int $id) : AbstractDomainObject;

    /**
     * Delete object from storage
     *
     * @param AbstractDomainObject $entity Object to delete
     *
     * @return void
     */
    abstract public function delete(AbstractDomainObject $entity);

    /**
     * Persist new object to storage
     *
     * @param AbstractDomainObject $entity Object to create
     *
     * @return int ID of newly created object
     */
    abstract protected function _create(AbstractDomainObject $entity) : int;

    /**
     * Update existing object in storage
     *
     * @param AbstractDomainObject $entity Object to update
     *
     * @return int ID of updated object
     */
    abstract protected function _update(AbstractDomainObject $entity) : int;
}
