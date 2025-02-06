<?php

namespace Foundation\Kernels\Http;

interface KernelHttp
{
    public function launchWeb(): void;
    public function loadRoutes(string $path): Kernel;
    public function registerExceptionOutput(string $pathTemplate): Kernel;
}