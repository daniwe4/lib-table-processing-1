<?php

declare(strict_types=1);

use CaT\Libs\TableProcessing;

/**
 * Wrap the protected methods to public.
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class DummyTableProcessor extends TableProcessing\TableProcessor
{
	public function wrapSaveRecord(TableProcessing\Record $record): TableProcessing\Record
	{
		return $this->createUpdateRecord($record);
	}

	public function wrapDeleteRecord(TableProcessing\Record $record)
	{
		$this->deleteRecord($record);
	}
}

/**
 * Tests for the class TableProcessor.
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class TableProcessorTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->backend = $this->createMock(TableProcessing\Backend::class);
		$this->process_object = $this->getMockBuilder("\\CaT\\Libs\\TableProcessing\\ProcessObject")->getMock();
	}

	public function testCreation()
	{
		$table = new TableProcessing\TableProcessor($this->backend);
	}

	public function testSaveRecordCreate()
	{
		$this->process_object
			->expects($this->once())
			->method("getId")
			->willReturn(-1)
		;

		$record = new TableProcessing\Record();
		$record = $record->withObject($this->process_object);

		$this->backend
			->expects($this->once())
			->method("create")
			->with($record)
			->willReturn($record)
		;

		$table = new DummyTableProcessor($this->backend);
		$table->wrapSaveRecord($record);
	}

	public function testSaveRecordUpdate()
	{
		$this->process_object
			->expects($this->once())
			->method("getId")
			->willReturn(11)
		;

		$record = new TableProcessing\Record();
		$record = $record->withObject($this->process_object);

		$this->backend
			->expects($this->once())
			->method("update")
			->with($record)
			->willReturn($record)
		;

		$table = new DummyTableProcessor($this->backend);
		$table->wrapSaveRecord($record);
	}

	public function testSaveRecordError()
	{
		$record = new TableProcessing\Record();
		$record = $record->withErrors(array(true));

		$this->backend
			->expects($this->once())
			->method("valid")
			->with($record)
			->willReturn($record)
		;

		$table = new DummyTableProcessor($this->backend);
		$result = $table->process(array($record), array("save"));

		$this->assertTrue(is_array($result));
	}

	public function testDeleteRecord()
	{
		$record = new TableProcessing\Record();

		$this->backend
			->expects($this->once())
			->method("delete")
			->with($record)
			->willReturn(null)
		;

		$table = new DummyTableProcessor($this->backend);
		$result = $table->wrapDeleteRecord($record);

		$this->assertTrue(is_null($result));
	}
}