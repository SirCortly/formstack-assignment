<?php

use PHPUnit\Framework\TestCase;
use App\{AbstractDomainObject, AbstractDataMapper};

class AbstractDataMapperTest extends TestCase
{
    /**
     * Test that constructor accepts PDO as parameter
     *
     * @covers \App\AbstractDataMapper::__construct
     */
    public function testConstruct()
    {
        $pdo = $this->createMock(\PDO::class);
        $mapper = $this->getMockForAbstractClass(AbstractDataMapper::class, [$pdo]);

        $this->assertInstanceOf(AbstractDataMapper::class, $mapper);
    }

    /**
     * Calling save() with and object where id is null will call _create
     *
     * @covers \App\AbstractDataMapper::save
     */
    public function testSaveObjectWithNullIdCallsCreate()
    {
        $object = $this->getMockForAbstractClass(AbstractDomainObject::class);

        $pdo = $this->createMock(\PDO::class);
        $mapper = $this->getMockForAbstractClass(AbstractDataMapper::class, [$pdo]);

        // We should expect _create to be called
        $mapper->expects($this->once())
            ->method('_create')
            ->will($this->returnValue(101));

        $this->assertNull($object->getId());
        $this->assertEquals(101, $mapper->save($object));
    }

    /**
     * Calling save() with an object where id is set will call _update
     *
     * @covers \App\AbstractDataMapper::save
     */
    public function testSaveObjectWithIdCallsUpdate()
    {
        $object = $this->getMockForAbstractClass(AbstractDomainObject::class);

        $pdo = $this->createMock(\PDO::class);
        $mapper = $this->getMockForAbstractClass(AbstractDataMapper::class, [$pdo]);

        $object->setId(101);

        // We should expect _create to be called
        $mapper->expects($this->once())
            ->method('_update')
            ->will($this->returnValue(101));

        $this->assertEquals(101, $mapper->save($object));
    }
}
