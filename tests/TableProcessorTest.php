<?php

use CaT\Libs\TableProcessing;

class DummyTableProcessor extends TableProcessing\TableProcessor
{
	public function wrapSaveRecord(array $record): array
	{
		return $this->saveRecord($record);
	}

	public function wrapDeleteRecord(array $record)
	{
		$this->deleteRecord($record);
	}
}

class TableProcessorTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->backend = $this->createMock(TableProcessing\Backend::class);
	}

	public function testCreation()
	{
		$table = new TableProcessing\TableProcessor($this->backend);
	}

	public function testSaveRecordCreate()
	{
		$record["object"] = $this->getMockBuilder("test")
			->setMethods(array("getId"))
			->getMock();

		$record["object"]
			->expects($this->once())
			->method("getId")
			->willReturn(-1)
		;

		$record['errors'] = array();

		$this->backend
			->expects($this->once())
			->method("valid")
			->with($record)
			->willReturn($record)
		;

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
		$record["object"] = $this->getMockBuilder("test")
			->setMethods(array("getId"))
			->getMock();

		$record["object"]
			->expects($this->once())
			->method("getId")
			->willReturn(11)
		;

		$record['errors'] = array();

		$this->backend
			->expects($this->once())
			->method("valid")
			->with($record)
			->willReturn($record)
		;

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
		$record['errors'] = array(true);

		$this->backend
			->expects($this->once())
			->method("valid")
			->with($record)
			->willReturn($record)
		;

		$table = new DummyTableProcessor($this->backend);
		$result = $table->saveRecord($record);

		$this->assertEquals($result, $record);
	}

	public function testDeleteRecord()
	{
		$record = array();

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