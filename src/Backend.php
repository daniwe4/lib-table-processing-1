<?php

declare(strict_types=1);

namespace CaT\Libs\TableProcessing;

/**
 * Backend interface for table processing.
 * Defines options how to act with table elements
 *
 * @author Stefan Hecken 	<stefan.hecken@concepts-and-training.de>
 */
interface Backend
{
	/**
	 * Delete the option in record
	 */
	public function delete(array $record);

	/**
	 * Checks option in record if it is valid
	 * If not fills key errors with values
	 */
	public function valid(array $record): array;

	/**
	 * Update an existing option
	 */
	public function update(array $record): array;

	/**
	 * Creates a new option
	 */
	public function create(array $record): array;
}
