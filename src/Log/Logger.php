<?php

namespace Foundation\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;

class Logger
{
    private const CHANNEL_NAME = 'app';
    private const MAX_FILES = 30;

    public function __invoke(): MonologLogger
    {
        $logger = new MonologLogger(self::CHANNEL_NAME);

        $handler = new RotatingFileHandler(
            filename: $this->getFilename(),
            maxFiles: self::MAX_FILES,
            level: $this->getLevel()
        );
        $handler->setFormatter($this->lineFormatter());

        return $logger->pushHandler($handler);
    }

    private function getLevel(): Level
    {
        return config('app.debug') ? Level::Debug : Level::Error;
    }

    private function getFilename(): string
    {
        return app()->storagePath('logs' . DIRECTORY_SEPARATOR . self::CHANNEL_NAME . '.log');
    }

    private function lineFormatter(): LineFormatter
    {
        return new LineFormatter(
            dateFormat: 'Y-m-d H:i:s, e',
            ignoreEmptyContextAndExtra: true,
            allowInlineLineBreaks: true
        );
    }

}