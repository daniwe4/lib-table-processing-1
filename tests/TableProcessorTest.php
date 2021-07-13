<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the class TableProcessor.
 */
class TableProcessorTest extends TestCase
{
    public function testCreation() : void
    {
        $backend = $this->createMock(Backend::class);
        $table = new TableProcessor($backend);
        $this->assertInstanceOf(TableProcessor::class, $table);
    }

    public function testDeleteProcess() : void
    {
        $action = TableProcessor::ACTION_DELETE;

        $backend = $this->createMock(Backend::class);
        $process_object = $this->createMock(ProcessObject::class);
        $process_object
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $record = new Record($process_object);
        $record = $record->withDelete(true);

        $table = new TableProcessor($backend);
        $records = $table->process([$record], [$action]);

        $this->assertEmpty($records);
    }

    public function testErrorsInProcess() : void
    {
        $action = TableProcessor::ACTION_SAVE;

        $backend = $this->createMock(Backend::class);
        $process_object = $this->createMock(ProcessObject::class);
        $error = $this->createMock(Error::class);
        $record = new Record($process_object);
        $record = $record->withError($error);

        $backend
            ->expects($this->once())
            ->method('valid')
            ->willReturn($record);

        $table = new TableProcessor($backend);
        $records = $table->process([$record], [$action]);
        $this->assertEquals([$error], $records[0]->getErrors());
    }

    public function testCreateProcess() : void
    {
        $action = TableProcessor::ACTION_SAVE;
        $backend = $this->createMock(Backend::class);
        $process_object = $this->createMock(ProcessObject::class);
        $process_object
            ->expects($this->once())
            ->method('getId')
            ->willReturn(-1);
        $new_object = $this->createMock(ProcessObject::class);
        $new_object
            ->expects($this->never())
            ->method('getId')
            ->willReturn(12);

        $record = new Record($process_object);
        $created_record = $record->withObject($new_object);

        $backend
            ->expects($this->once())
            ->method('valid')
            ->willReturn($record);
        $backend
            ->expects($this->once())
            ->method('create')
            ->willReturn($created_record);

        $table = new TableProcessor($backend);
        $records = $table->process([$record], [$action]);

        $this->assertEquals($created_record, $records[0]);
    }

    public function testUpdateProcess() : void
    {
        $action = TableProcessor::ACTION_SAVE;
        $backend = $this->createMock(Backend::class);
        $process_object = $this->createMock(ProcessObject::class);

        $process_object
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $record = new Record($process_object);

        $backend
            ->expects($this->once())
            ->method('valid')
            ->willReturn($record);
        $backend
            ->expects($this->once())
            ->method('update')
            ->willReturn($record);

        $table = new TableProcessor($backend);
        $records = $table->process([$record], [$action]);

        $this->assertEquals($record, $records[0]);
    }
}