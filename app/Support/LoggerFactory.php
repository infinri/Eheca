<?php

namespace App\Support;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

/**
 * Provides a singleton PSR-3 logger that writes JSON-formatted lines to the
 * default PHP error log. No external services required.
 */
class LoggerFactory
{
    private static ?Logger $logger = null;

    public static function get(): Logger
    {
        if (self::$logger === null) {
            $handler = new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::INFO);
            $handler->setFormatter(new JsonFormatter());

            self::$logger = new Logger('app');
            self::$logger->pushHandler($handler);
        }

        return self::$logger;
    }
}
