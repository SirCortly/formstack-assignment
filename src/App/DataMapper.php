<?php
namespace App;

abstract class DataMapper
{
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
