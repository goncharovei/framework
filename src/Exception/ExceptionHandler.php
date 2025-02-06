<?php

namespace Foundation\Exception;

use Illuminate\Contracts\Container\BindingResolutionException;

final class ExceptionHandler
{
    private ExceptionOutput|null $output = null;

    public function __construct(private ExceptionLogger $logger)
    {
    }

    public function __invoke(): ExceptionHandler
    {
        error_reporting(E_ALL);

        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);

        if (!config('app.debug'))
        {
            ini_set('display_errors', 'Off');
        }

        return $this;
    }

    public function setOutput(ExceptionOutput $output): void
    {
        $this->output = $output;
    }

    /**
     * @throws BindingResolutionException
     */
    public function handleException(\Throwable $exception)
    {
        $this->logger->write($exception);
        $this->output?->show($exception);
    }

    /**
     *  Convert PHP errors to ErrorException instances.
     *
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @return false
     * @throws \ErrorException
     */
    public function handleError(int $level, string $message, string $file = '', int $line = 0): false
    {
        if (!(error_reporting() & $level))
        {
            return false;
        }

        throw new \ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * @throws \ErrorException
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();
        if(empty($error) || !$this->isFatal($error['type']))
        {
            return;
        }

        throw new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
    }

    private function isFatal(int $type): bool
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }

}