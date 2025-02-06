<?php

namespace Foundation\Exception;

use Psr\Log\LoggerInterface;

class ExceptionLogger
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function write(\Throwable $error): void
    {
        try {
            $this->logger->error(
                $this->getErrorText($error)
            );
        } catch (\Throwable $e) {
            error_log($this->getErrorText($e));
        }
    }

    private function getErrorText(\Throwable $e): string
    {
        return $e->getMessage() . PHP_EOL .
            $e->getFile() . '(' . $e->getLine() . ').' . ' Code: ' . $e->getCode() . '.' . PHP_EOL .
            $e->getTraceAsString() . PHP_EOL;
    }
}