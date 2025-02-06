<?php

namespace Foundation\Exception;

interface ExceptionOutput
{
    public function show(\Throwable $exception): void;
}