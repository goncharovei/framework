<?php

namespace Foundation\Exception\Http;

final readonly class ExceptionFields
{
    public function __construct(
        public int $code,
        public string $message = '',
        public string $trace = ''
    )
    {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}