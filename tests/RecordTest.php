<?php

declare(strict_types=1);

use CaT\Libs\TableProcessing;

class Test implements TableProcessing\ProcessObject
{
	public function getId(): int
	{
		return 11;
	}
}

/**
 * Tests for the class Record.
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class RecordTest extends PHPUnit_Framework_TestCase
{
	public function testCreate()
	{
		$object = new TableProcessing\Record();
		$this->assertEquals($object->getObject(), null);
		$this->assertEquals($object->getErrors(), array());
		$this->assertEquals($object->getDelete(), false);
		$this->assertEquals($object->getMessages(), array());

		return $object;
	}

	/**
	 * @depends testCreate
	 */
	public function testWithObject(TableProcessing\Record $object)
	{
		$test = new Test();
		$new_object = $object->withObject($test);

		$this->assertEquals($object->getObject(), null);
		$this->assertEquals($object->getErrors(), array());
		$this->assertEquals($object->getDelete(), false);
		$this->assertEquals($object->getMessages(), array());

		$this->assertEquals($new_object->getObject(), $test);
		$this->assertEquals($new_object->getErrors(), array());
		$this->assertEquals($new_object->getDelete(), false);
		$this->assertEquals($new_object->getMessages(), array());
	}

	/**
	 * @depends testCreate
	 */
	public function testWithErrors(TableProcessing\Record $object)
	{
		$new_object = $object->withErrors(array("test"));

		$this->assertEquals($object->getObject(), null);
		$this->assertEquals($object->getErrors(), array());
		$this->assertEquals($object->getDelete(), false);
		$this->assertEquals($object->getMessages(), array());

		$this->assertEquals($new_object->getObject(), null);
		$this->assertEquals($new_object->getErrors(), array("test"));
		$this->assertEquals($new_object->getDelete(), false);
		$this->assertEquals($new_object->getMessages(), array());
	}

	/**
	 * @depends testCreate
	 */
	public function testWithDelete(TableProcessing\Record $object)
	{
		$new_object = $object->withDelete(true);

		$this->assertEquals($object->getObject(), null);
		$this->assertEquals($object->getErrors(), array());
		$this->assertEquals($object->getDelete(), false);
		$this->assertEquals($object->getMessages(), array());

		$this->assertEquals($new_object->getObject(), null);
		$this->assertEquals($new_object->getErrors(), array());
		$this->assertEquals($new_object->getDelete(), true);
		$this->assertEquals($new_object->getMessages(), array());
	}

	/**
	 * @depends testCreate
	 */
	public function testWithMessages(TableProcessing\Record $object)
	{
		$new_object = $object->withMessages(array("Ein Test"));

		$this->assertEquals($object->getObject(), null);
		$this->assertEquals($object->getErrors(), array());
		$this->assertEquals($object->getDelete(), false);
		$this->assertEquals($object->getMessages(), array());

		$this->assertEquals($new_object->getObject(), null);
		$this->assertEquals($new_object->getErrors(), array());
		$this->assertEquals($new_object->getDelete(), false);
		$this->assertEquals($new_object->getMessages(), array("Ein Test"));
	}
}