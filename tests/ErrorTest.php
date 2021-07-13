<?php declare(strict_types=1);

/* Copyright (c) 2021 - Stefan Hecken <stefan.hecken@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testCreate()
    {
        $error = new Error("component", "this is a vault");
        $this->assertEquals("component", $error->getComponent());
        $this->assertEquals("this is a vault", $error->getMessage());
    }
}