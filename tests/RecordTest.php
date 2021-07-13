<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the class Record.
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class RecordTest extends TestCase
{
    public function testCreate() : Record
    {
        $object = $this->createMock(ProcessObject::class);
        $record = new Record($object);
        $this->assertSame($object, $record->getObject());
        $this->assertEmpty($record->getErrors());
        $this->assertEmpty($record->getMessages());
        $this->assertFalse($record->getDelete());

        return $record;
    }

    /**
     * @depends testCreate
     */
    public function testWithErrors(Record $record) : void
    {
        $error = new Error("component", "this is a fault");
        $new_record = $record->withError($error);

        $this->assertEmpty($record->getErrors());
        $this->assertEmpty($record->getMessages());
        $this->assertFalse($record->getDelete());

        $this->assertEquals([$error], $new_record->getErrors());
        $this->assertEmpty($record->getMessages());
        $this->assertFalse($record->getDelete());
    }

    /**
     * @depends testCreate
     */
    public function testWithMessages(Record $record) : void
    {
        $message = new Message("this is a message");
        $new_record = $record->withMessage($message);

        $this->assertEmpty($record->getErrors());
        $this->assertEmpty($record->getMessages());
        $this->assertFalse($record->getDelete());

        $this->assertEmpty($record->getErrors());
        $this->assertEquals([$message], $new_record->getMessages());
        $this->assertFalse($record->getDelete());
    }

    /**
     * @depends testCreate
     */
    public function testWithDelete(Record $record) : void
    {
        $new_record = $record->withDelete(true);

        $this->assertEmpty($record->getErrors());
        $this->assertEmpty($record->getMessages());
        $this->assertFalse($record->getDelete());

        $this->assertEmpty($new_record->getErrors());
        $this->assertEmpty($new_record->getMessages());
        $this->assertTrue($new_record->getDelete());
    }

    /**
     * @depends testCreate
     */
    public function testErrorsByComponent(Record $record)
    {
        $error = new Error("component", "this is a fault");
        $error2 = new Error("component", "this is a fault again");
        $error3 = new Error("component2", "this is another fault");
        $error4 = new Error("component2", "this is a freaky fault");
        $record = $record->withError($error)
                         ->withError($error2)
                         ->withError($error3)
                         ->withError($error4);

        $filtered_errors = $record->getErrorsByComponent("component");
        $this->assertEquals(2, count($filtered_errors));
        $this->assertSame([$error, $error2], $filtered_errors);
    }
}