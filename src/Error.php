<?php declare(strict_types=1);

/* Copyright (c) 2021 - Stefan Hecken <stefan.hecken@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

class Error
{
    /**
     * @var string
     */
    protected $component;

    /**
     * @var string
     */
    protected $message;

    public function __construct(string $component, string $message)
    {
        $this->component = $component;
        $this->message = $message;
    }

    public function getComponent() : string
    {
        return $this->component;
    }

    public function getMessage() : string
    {
        return $this->message;
    }
}