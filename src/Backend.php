<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

/**
 * Backend interface for table processing.
 * Defines options how to act with table elements
 * @author Stefan Hecken    <stefan.hecken@concepts-and-training.de>
 */
interface Backend
{
    /**
     * Delete Record from db.
     */
    public function delete(Record $record) : void;

    /**
     * Validate the record and if errors occur fill the error property.
     * Fill the error property like this:
     *    $record->withError(array("element name" => array("Error Message")))
     */
    public function valid(Record $record) : Record;

    /**
     * Update a Record in db.
     */
    public function update(Record $record) : Record;

    /**
     * Create a new record in db.
     */
    public function create(Record $record) : Record;
}
