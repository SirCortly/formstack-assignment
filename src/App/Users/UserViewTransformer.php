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
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'fullname' => $user->getFullName(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => is_null($user->getUpdatedAt())
                ? null
                : $user->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
