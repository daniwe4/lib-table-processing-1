<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */
/* Copyright (c) 2021 - Stefan Hecken <stefan.hecken@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

/**
 * Decide how to work with table line.
 */
class TableProcessor
{
    const ACTION_SAVE = "save";
    const ACTION_DELETE = "delete";

    /**
     * @var Backend
     */
    protected $backend;

    public function __construct(Backend $backend)
    {
        $this->backend = $backend;
    }

    /**
     * Execute process for delete or save/create
     */
    public function process(array $records, array $actions) : array
    {
        $delete = in_array(self::ACTION_DELETE, $actions);
        $save = in_array(self::ACTION_SAVE, $actions);

        foreach ($records as $key => $record) {
            if ($delete && $record->getDelete() && $record->getObject()->getId() != -1) {
                $this->deleteRecord($record);
                unset($records[$key]);
            }

            if ($save && !$record->getDelete()) {
                $record = $this->backend->valid($record);

                if (count($record->getErrors()) > 0) {
                    $records[] = $record;
                } else {
                    $records[] = $this->createUpdateRecord($record);
                }
            }
        }

        return $records;
    }

    protected function createUpdateRecord(Record $record) : Record
    {
        if ($record->getObject()->getId() == -1) {
            return $this->backend->create($record);
        } else {
            return $this->backend->update($record);
        }
    }

    protected function deleteRecord(Record $record) : void
    {
        $this->backend->delete($record);
    }
}
