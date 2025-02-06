<?php

namespace Foundation\Exception\Http;

abstract class ExceptionFieldPrototype
{
    public function __construct(protected \Throwable $error)
    {
    }

    abstract public function getFields(): ExceptionFields;

    protected function getCode(): int
    {
        return $this->error->getCode() ?: 500;
    }
}