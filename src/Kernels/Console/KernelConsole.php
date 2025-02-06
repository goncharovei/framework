<?php

namespace Foundation\Kernels\Console;

interface KernelConsole
{
    public function addCommandPaths(array $paths): KernelConsole;
    public function launchConsole(): int;
}