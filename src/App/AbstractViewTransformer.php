<?php
namespace App;

use App\AbstractDomainObject;

abstract class AbstractViewTransformer
{
    /**
     * Transform an entity to output array
     *
     * @param AbstractDomainObject $entity Entity to transform
     */
    abstract public function transform(AbstractDomainObject $entity) : array;
}
