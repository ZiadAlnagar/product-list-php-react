<?php

declare(strict_types=1);

namespace Core;

use Core\Exception\ConfigException;
use Core\Interface\ConfigInterface;
use Core\Interface\LoggerInterface;
use Exception;

class Config implements ConfigInterface
{
    private static ?Config $instance = null;


    private static array $config;


    private static LoggerInterface $logger;


    private function __construct(LoggerInterface $logger = null)
    {
        if ($logger) {
            self::$logger = $logger;
        }
        $this->loadEnv();
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


    /**
     * @return mixed
     */
    public static function get(string $key, string $default = null)
    {
        if (! self::$instance) {
            self::load();
        }
        $path = explode('.', $key);
        $currConf = null;
        try {
            foreach ($path as $i => $k) {
                if ($i === 0) {
                    if ($default === null) {
                        throw new ConfigException($key);
                    }
                    $currConf = self::$config[$k] ?? $default;
                    continue;
                }
                if ($default === null) {
                    throw new ConfigException($key);
                }
                $currConf = $currConf[$k] ?? $default;
            }
        } catch (ConfigException $e) {
            self::log($e);
        }
        return $currConf;
    }


    public static function getAll(): array
    {
        if (! self::$config) {
            self::load();
        }
        return self::$config;
    }

    /**
     * Loads config files from /config/*.php
     *
     * The function assumes config files location in root folder.
     * Some config files use `env()` from `/core/Helpers.php`,
     * so we require it to avoid errors
     */
    private function loadEnv()
    {
        $configArr = [];
        require __DIR__ . '/Helpers.php';
        $files = glob(__DIR__ . '/../config/*.{php}', GLOB_BRACE);
        foreach ($files as $file) {
            $confName = basename($file, '.php');
            $configArr[$confName] = require $file;
        }
        self::$config = $configArr;
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
