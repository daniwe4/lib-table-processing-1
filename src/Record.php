<?php

declare(strict_types=1);

namespace CaT\Libs\TableProcessing;

/**
 * Data holding class for processing relevant data.
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class Record
{
	/**
	 * @var object
	 */
	protected $object;

	/**
	 * @var array
	 */
	protected $errors;

	/**
	 * @var bool
	 */
	protected $delete;

	/**
	 * @var array
	 */
	protected $messages;

	public function __construct()
	{
		$this->object = null;
		$this->errors = array();
		$this->delete = false;
		$this->messages = array();
	}

	public function getObject()
	{
		return $this->object;
	}

	public function withObject(ProcessObject $object): Record
	{
		$clone = clone $this;
		$clone->object = $object;
		return $clone;
	}

	public function getErrors(): array
	{
		return $this->errors;
	}

	public function withErrors(array $errors): Record
	{
		$clone = clone $this;
		$clone->errors = $errors;
		return $clone;
	}

	public function getDelete(): bool
	{
		return $this->delete;
	}

	public function withDelete(bool $delete): Record
	{
		$clone = clone $this;
		$clone->delete = $delete;
		return $clone;
	}

	public function getMessages(): array
	{
		return $this->messages;
	}

	public function withMessages(array $messages): Record
	{
		$clone = clone $this;
		$clone->messages = $messages;
		return $clone;
	}
}