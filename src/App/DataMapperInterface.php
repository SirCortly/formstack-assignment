<?php
namespace App;

interface DataMapperInterface
{
    /**
     * Fetch all objects of specific DomainObject type
     *
     * @return DomainObject[]
     */
    public function fetchAll() : array;

    /**
     * Fetch an object of a specific DomainObject type by ID
     *
     * @param int $id ID of DomainObject
     *
     * @return DomainObject
     *
     */
    public function fetchById(int $id) : DomainObject;

    /**
     * Persist a DomainObject to storage
     *
     * @param DomainObject $entity Object to persist
     *
     * @return DomainObject Newly created or updated DomainObject
     *
     */
    public function save(DomainObject $entity) : DomainObject;

    /**
     * Delete DomainObject from storage
     *
     * @param DomainObject $entity Object to delete
     *
     * @return void
     */
    public function delete(DomainObject $entity);
}
