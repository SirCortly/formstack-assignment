<?php
namespace App\Users;

use App\{DomainObject, DataMapper};

class UserDataMapper extends DataMapper
{
    /**
     * Delete User
     *
     * @param DomainObject $user User to delete
     *
     * @return void
     */
    public function delete(DomainObject $user)
    {
        return true;
    }

    /**
     * Create new User
     *
     * @param DomainObject $user User to create
     *
     * @return int New User ID
     */
    protected function _create(DomainObject $user) : int
    {
        return 101;
    }

    /**
     * Update existing User
     *
     * @param DomainObject $user User to updated
     *
     * @return int Updated User ID
     */
    protected function _update(DomainObject $user) : int
    {
        return 102;
    }
}
