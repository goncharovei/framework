<?php

namespace Foundation\Exception\Http;

class ExceptionFieldShow extends ExceptionFieldPrototype
{

    public function getFields(): ExceptionFields
    {
        return new ExceptionFields(
            $this->getCode(),
            $this->getMessage(),
            $this->getTrace()
        );
    }

    private function getMessage(): string
    {
        return $this->error->getMessage() . ' ' .
            $this->error->getFile() .
            '(' . $this->error->getLine() . ').';
    }

    private function getTrace(): string
    {
        return nl2br($this->error->getTraceAsString());
    }
}