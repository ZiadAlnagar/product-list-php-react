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
    /**
     * @var Env|null
     */
    private static ?Env $instance = null;

    /**
     * @var Dotenv
     */
    private static Dotenv $dotenv;

    /**
     * @var LoggerInterface|null
     */
    private static ?LoggerInterface $logger = null;

    /**
     * @param  LoggerInterface|null $logger
     */
    private function __construct(LoggerInterface $logger = null)
    {
        if ($logger)
            self::$logger = $logger;
        try {
            self::$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
            self::$dotenv->load();
        } catch (Exception  $e) {
            self::log($e);
        }
    }

    /**
     * @param  LoggerInterface|null $logger
     * @return Env
     */
    public static function load(LoggerInterface $logger = null): Env
    {
        if (!self::$instance)
            self::$instance = new self($logger);
        else if ($logger && !self::$logger)
            self::$logger = $logger;
        return self::$instance;
    }

    /**
     * @return Dotenv
     */
    public function getDotEnv(): Dotenv
    {
        return self::$dotenv;
    }

    /**
     * @param  string      $key
     * @param  string|null $default
     * @return string
     */
    public static function get(string $key, string $default = null): string
    {
        try {
            if (isset($_ENV[$key]))
                if (!empty($_ENV[$key]))
                    return $_ENV[$key];
            if (!$default)
                throw new EnvException($key);
            return $default;
        } catch (Exception  $e) {
            self::log($e);
        }
    }

    /**
     * @param  Exception|string $message
     * @return void
     */
    private static function log(Exception|string $message): void
    {
        if (!self::$logger) {
        } else self::$logger->log($message);
    }
}
