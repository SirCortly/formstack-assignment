<?php

use PHPUnit\Framework\TestCase;
use App\{AbstractDomainObject, AbstractViewTransformer};

class AbstractViewTransformerTest extends TestCase
{
    /**
    * Test that collection is transformed to array
    *
    * @covers \App\AbstractViewTransformer::transformCollection
    */
    public function testTransformCollection()
    {
        $transformer = $this->getMockForAbstractClass(AbstractViewTransformer::class);

        // Set up a collection with two domain objects
        $collection = [
            $this->getMockForAbstractClass(AbstractDomainObject::class),
            $this->getMockForAbstractClass(AbstractDomainObject::class)
        ];

        // Expect transform to be called twice, once on each domain object
        $transformer->expects($this->exactly(2))
            ->method('transform')
            ->will($this->returnValue(['transformed' => 'values']));

        $result = $transformer->transformCollection($collection);

        // We should end up with a multidimensional array
        // with one set of values for each object in collection
        $this->assertEquals([
            [
                'transformed' => 'values'
            ],
            [
                'transformed' => 'values'
            ]
        ], $result);
    }
}
