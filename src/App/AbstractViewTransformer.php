<?php
namespace App;

use App\AbstractDomainObject;

abstract class AbstractViewTransformer
{
    /**
     * Transform a collection of entities to output array
     *
     * @param AbstractDomainObject[] $entities Entities to transform
     */
    public function transformCollection(array $entities)
    {
        return array_map(function($entity) {
            return $this->transform($entity);
        }, $entities);
    }

    /**
     * Transform an entity to output array
     *
     * @param AbstractDomainObject $entity Entity to transform
     */
    abstract public function transform(AbstractDomainObject $entity) : array;
}
