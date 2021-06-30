<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the class TableProcessor.
 */
class TableProcessorTest extends TestCase
{
    /**
     * @var Backend|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $backend;

    /**
     * @var ProcessObject|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $process_object;

	public function setUp() : void
	{
		$this->backend = $this->createMock(Backend::class);
		$this->process_object = $this->getMockBuilder("\\CaT\\Libs\\TableProcessing\\ProcessObject")->getMock();
	}

	public function testCreation() : void
	{
	    $table = new TableProcessor($this->backend);
	    $this->assertInstanceOf(TableProcessor::class, $table);
	}

	public function testDeleteProcess() : void
    {
        $action = TableProcessor::ACTION_DELETE;

        $this->process_object
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1)
        ;

        $record = new Record();
        $record = $record
            ->withObject($this->process_object)
            ->withDelete(true)
        ;

        $table = new TableProcessor($this->backend);
        $records = $table->process([$record], [$action]);

        $this->assertEmpty($records);
    }

    public function testErrorsInProcess() : void
    {
        $action = TableProcessor::ACTION_SAVE;

        $record = new Record();
        $record = $record
            ->withDelete(false)
            ->withErrors(['error'])
        ;

        $this->backend
            ->expects($this->once())
            ->method('valid')
            ->willReturn($record)
        ;

        $table = new TableProcessor($this->backend);
        $records = $table->process([$record], [$action]);
        $error = $records[0]->getErrors();

        $this->assertEquals('error', $error[0]);
    }

    public function testCreateProcess() : void
    {
        $action = TableProcessor::ACTION_SAVE;

        $this->process_object
            ->expects($this->once())
            ->method('getId')
            ->willReturn(-1)
        ;

        $record = new Record();
        $record = $record
            ->withDelete(false)
            ->withObject($this->process_object)
        ;

        $this->backend
            ->expects($this->once())
            ->method('valid')
            ->willReturn($record)
        ;
        $this->backend
            ->expects($this->once())
            ->method('create')
            ->willReturn($record)
        ;

        $table = new TableProcessor($this->backend);
        $records = $table->process([$record], [$action]);

        $this->assertEquals($record, $records[0]);
    }

    public function testUpdateProcess() : void
    {
        $action = TableProcessor::ACTION_SAVE;

        $this->process_object
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1)
        ;

        $record = new Record();
        $record = $record
            ->withDelete(false)
            ->withObject($this->process_object)
        ;

        $this->backend
            ->expects($this->once())
            ->method('valid')
            ->willReturn($record)
        ;
        $this->backend
            ->expects($this->once())
            ->method('update')
            ->willReturn($record)
        ;

        $table = new TableProcessor($this->backend);
        $records = $table->process([$record], [$action]);

        $this->assertEquals($record, $records[0]);
    }
}