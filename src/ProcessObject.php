<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

/**
 * Data object representing the db record have to implement this interface.
 */
interface ProcessObject
{
	public function getId() : int;
}