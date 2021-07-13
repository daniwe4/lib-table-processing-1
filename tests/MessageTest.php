<?php declare(strict_types=1);

/* Copyright (c) 2021 - Stefan Hecken <stefan.hecken@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testCreate()
    {
        $error = new Message("this is a message");
        $this->assertEquals("this is a message", $error->getMessage());
    }
}