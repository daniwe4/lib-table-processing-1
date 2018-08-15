<?php

declare(strict_types=1);

namespace CaT\Libs\TableProcessing;

/**
 * Decide how to work with table line.
 *
 * @author Stefan Hecken 	<stefan.hecken@concepts-and-training.de>
 */
class TableProcessor
{
	const ACTION_SAVE = "save";
	const ACTION_DELETE = "delete";

	public function __construct(Backend $backend)
	{
		$this->backend = $backend;
	}

	/**
	 * Execute process for delete or save/create
	 */
	public function process(array $records, array $actions): array
	{
		$delete = in_array(self::ACTION_DELETE, $actions);
		$save = in_array(self::ACTION_SAVE, $actions);

		foreach ($records as $key => $record) {
			if ($delete && $record["delete"] && $record["object"]->getId() != -1) {
				$this->deleteRecord($record);
				unset($records[$key]);
			}

			if ($save && !$record["delete"]) {
				$records[$key] = $this->saveRecord($record);
			}
		}

		return $records;
	}

	/**
	 * Saves or creates a new record
	 */
	protected function saveRecord(array $record): array
	{
		$record = $this->backend->valid($record);

		if (count($record["errors"]) > 0) {
			return $record;
		}

		if ($record["object"]->getId() == -1) {
			return $this->backend->create($record);
		} else {
			return $this->backend->update($record);
		}
	}

	/**
	 * Deletes a record
	 *
	 * @param mixed[] 	$record
	 *
	 * @return null
	 */
	protected function deleteRecord(array $record)
	{
		$this->backend->delete($record);
	}
}