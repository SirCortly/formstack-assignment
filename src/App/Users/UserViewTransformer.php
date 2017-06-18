<?php
namespace App\Users;

use App\{AbstractDomainObject, AbstractViewTransformer};

class UserViewTransformer extends AbstractViewTransformer
{
    /**
     * Transform a User to output array
     *
     * @param AbstractDomainObject $user User to transform
     */
    public function transform(AbstractDomainObject $user) : array
    {
        return [
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'fullname' => $user->getFullName()
        ];
    }
}
