<?php

use PHPUnit\Framework\TestCase;
use App\DomainObject;
use Exception;

class DomainObjectTest extends TestCase
{
    /**
     * Test that object is initialized with $id = null;
     */
    public function testIdInitiallyNull()
    {
        $domain_object = $this->getMockForAbstractClass(DomainObject::class);

        $this->assertNull($domain_object->getId());
    }

    /**
     * Test that setter and getter for $id function properly
     */
    public function testSetGetID()
    {
        $domain_object = $this->getMockForAbstractClass(DomainObject::class);
        $domain_object->setId(101);

        $this->assertEquals(101, $domain_object->getId());
    }

    /**
     * Test that an exception is thrown if we try to set an $id that is not null
     */
    public function testSetExistingIdThrowsException()
    {
        $this->expectException(Exception::class);

        $domain_object = $this->getMockForAbstractClass(DomainObject::class);

        $domain_object->setId(101);
        $domain_object->setId(102);
    }
}
