<?php declare(strict_types=1);

/* Copyright (c) 2021 - Daniel Weise <daniel.weise@concepts-and-training.de> - Extended GPL, see LICENSE */

namespace CaT\Libs\TableProcessing;

/**
 * Data holding class for processing relevant data.
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class Record
{
    /**
     * @var ProcessObject
     */
    protected $object;

    /**
     * @var Error[]
     */
    protected $errors;

    /**
     * @var bool
     */
    protected $delete;

    /**
     * @var Message[]
     */
    protected $messages;

    public function __construct(ProcessObject $object)
    {
        $this->object = $object;
        $this->errors = [];
        $this->messages = [];
        $this->delete = false;
    }

    public function getObject() : ProcessObject
    {
        return $this->object;
    }

    public function withObject(ProcessObject $object) : Record
    {
        $clone = clone $this;
        $clone->object = $object;
        return $clone;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function withError(Error $error) : Record
    {
        $clone = clone $this;
        $clone->errors[] = $error;
        return $clone;
    }

    public function getMessages() : array
    {
        return $this->messages;
    }

    public function withMessage(Message $message) : Record
    {
        $clone = clone $this;
        $clone->messages[] = $message;
        return $clone;
    }

    public function getDelete() : bool
    {
        return $this->delete;
    }

    public function withDelete(bool $delete) : Record
    {
        $clone = clone $this;
        $clone->delete = $delete;
        return $clone;
    }

    public function getErrorsByComponent(string $component) : array
    {
        return array_filter(
            $this->errors,
            function (Error $error) use ($component) {
                return $error->getComponent() === $component;
            }
        );
    }
}