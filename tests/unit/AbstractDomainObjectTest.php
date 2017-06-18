<?php

use PHPUnit\Framework\TestCase;
use App\AbstractDomainObject;

class AbstractDomainObjectTest extends TestCase
{
    /**
     * Test that object is initialized with $id = null;
     *
     * @covers \App\AbstractDomainObject::getId
     */
    public function testIdInitiallyNull()
    {
        $domain_object = $this->getMockForAbstractClass(AbstractDomainObject::class);

        $this->assertNull($domain_object->getId());
    }

    /**
     * Test that setter and getter for $id function properly
     *
     * @covers \App\AbstractDomainObject::getId
     * @covers \App\AbstractDomainObject::setId
     */
    public function testSetGetID()
    {
        $domain_object = $this->getMockForAbstractClass(AbstractDomainObject::class);
        $domain_object->setId(101);

        $this->assertEquals(101, $domain_object->getId());
    }

    /**
     * Test that an exception is thrown if we try to set an $id that is not null
     *
     * @covers \App\AbstractDomainObject::setId
     */
    public function testSetExistingIdThrowsException()
    {
        $this->expectException(Exception::class);

        $domain_object = $this->getMockForAbstractClass(AbstractDomainObject::class);

        $domain_object->setId(101);
        $domain_object->setId(102);
    }

    /**
     * Test getter and setter for created_at
     *
     * @covers \App\AbstractDomainObject::getCreatedAt
     * @covers \App\AbstractDomainObject::setCreatedAt
     */
    public function testGetSetCreatedAt()
    {
        $domain_object = $this->getMockForAbstractClass(AbstractDomainObject::class);
        $domain_object->setCreatedAt(new \DateTime('NOW'));

        $this->assertInstanceOf(\DateTime::class, $domain_object->getCreatedAt());
    }

    /**
     * Test getter and setter for updated_at
     *
     * @covers \App\AbstractDomainObject::getUpdatedAt
     * @covers \App\AbstractDomainObject::setUpdatedAt
     */
    public function testGetSetUpdatedAt()
    {
        $domain_object = $this->getMockForAbstractClass(AbstractDomainObject::class);
        $domain_object->setUpdatedAt(new \DateTime('NOW'));

        $this->assertInstanceOf(\DateTime::class, $domain_object->getUpdatedAt());
    }
}
