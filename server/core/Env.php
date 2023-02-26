<?php

declare(strict_types=1);

namespace Core;

use Core\Exception\EnvException;
use Core\Interface\EnvInterface;
use Core\Interface\LoggerInterface;
use Dotenv\Dotenv;
use Exception;

class Env implements EnvInterface
{
    private static ?Env $instance = null;


    private static Dotenv $dotenv;


    private static ?LoggerInterface $logger = null;


    private function __construct(LoggerInterface $logger = null)
    {
        if ($logger) {
            self::$logger = $logger;
        }
        try {
            self::$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
            self::$dotenv->load();
        } catch (Exception  $e) {
            self::log($e);
        }
    }


    public static function load(LoggerInterface $logger = null): self
    {
        if (! self::$instance) {
            self::$instance = new self($logger);
        } elseif ($logger && ! self::$logger) {
            self::$logger = $logger;
        }
        return self::$instance;
    }


    public function getDotEnv(): Dotenv
    {
        return self::$dotenv;
    }


    public static function get(string $key, string $default = null): string
    {
        try {
            if (isset($_ENV[$key])) {
                if (! empty($_ENV[$key])) {
                    return $_ENV[$key];
                }
            }
            if (! $default) {
                throw new EnvException($key);
            }
            return $default;
        } catch (Exception  $e) {
            self::log($e);
        }
    }


    /**
     * @param \Exception|string $message
     */
    private static function log($message): void
    {
        if (! self::$logger) {
        } else {
            self::$logger->log($message);
        }
    }
}
