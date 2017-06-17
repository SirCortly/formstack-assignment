<?php
namespace App;

abstract class DataMapper
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
     * Persist DomainObject to storage
     *
     * Determine whether an object should be created or updated
     * and act accordingly.
     *
     * @param DomainObject $entity Object to persist
     *
     * @return int ID of persisted object
     *
     */
    public function save(DomainObject $entity) : int
    {
        if (is_null($entity->getId())) {
            return $this->_create($entity);
        }
        return $this->_update($entity);
    }

    /**
     * Get all instances of DomainObject type
     *
     * @return DomainObject[]
     */
    abstract public function fetchAll() : array;

    /**
     * Get a specific instance of DomainObject by ID
     *
     * @return DomainObject
     */
    abstract public function fetchById(int $id) : DomainObject;

    /**
     * Delete object from storage
     *
     * @param DomainObject $entity Object to delete
     *
     * @return void
     */
    abstract public function delete(DomainObject $entity);

    /**
     * Persist new object to storage
     *
     * @param DomainObject $entity Object to create
     *
     * @return int ID of newly created object
     */
    abstract protected function _create(DomainObject $entity) : int;

    /**
     * Update existing object in storage
     *
     * @param DomainObject $entity Object to update
     *
     * @return int ID of updated object
     */
    abstract protected function _update(DomainObject $entity) : int;
}
