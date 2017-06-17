<?php

use PHPUnit\Framework\TestCase;
use App\{DomainObject, DataMapper};

class DataMapperTest extends TestCase
{
    /**
     * Calling save() with and object where id is null will call _create
     */
    public function testSaveObjectWithNullIdCallsCreate()
    {
        $object = $this->getMockForAbstractClass(DomainObject::class);

        $pdo = $this->createMock(\PDO::class);
        $mapper = $this->getMockForAbstractClass(DataMapper::class, [$pdo]);

        // We should expect _create to be called
        $mapper->expects($this->once())
            ->method('_create')
            ->will($this->returnValue(101));

        $this->assertNull($object->getId());
        $this->assertEquals(101, $mapper->save($object));
    }

    /**
     * Calling save() with an object where id is set will call _update
     */
    public function testSaveObjectWithIdCallsUpdate()
    {
        $object = $this->getMockForAbstractClass(DomainObject::class);

        $pdo = $this->createMock(\PDO::class);
        $mapper = $this->getMockForAbstractClass(DataMapper::class, [$pdo]);

        $object->setId(101);

        // We should expect _create to be called
        $mapper->expects($this->once())
            ->method('_update')
            ->will($this->returnValue(101));

        $this->assertEquals(101, $mapper->save($object));
    }
}
