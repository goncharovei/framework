<?php

namespace Foundation\Exception\Http;

use Foundation\Kernels\Http\Kernel;

final readonly class ExceptionFieldFactory
{
    public function __construct(private Kernel $kernel)
    {
    }

    public function create(\Throwable $error): ExceptionFieldPrototype
    {
        return $this->isDisableDetails() ?
            new ExceptionFieldHide($error) :
            new ExceptionFieldShow($error);
    }

    private function isDisableDetails(): bool
    {
        return !$this->kernel->getApplication()->hasDebugModeEnabled();
    }
}