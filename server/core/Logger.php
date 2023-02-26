<?php

declare(strict_types=1);

namespace Core;

use Core\Interface\LoggerInterface;
use Error;
use Exception;

class Logger implements LoggerInterface
{
    private static $logFile = __DIR__ . '/../logs/test.log';


    public static function log(string|Exception $message): void
    {
        try {
            error_log($message->getMessage() . PHP_EOL, 3, self::$logFile);
        } catch (Error $e) {
            error_log($message . PHP_EOL, 3, self::$logFile);
        }
    }
}
