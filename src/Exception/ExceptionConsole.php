<?php

namespace Foundation\Exception;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Output\OutputInterface;

final class ExceptionConsole implements ExceptionOutput
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function show(\Throwable $exception): void
    {
        (new ConsoleApplication)->renderThrowable($exception, $this->output);
    }
}