<?php

declare(strict_types=1);

namespace Core;

use Core\Interface\ConfigInterface;
use Core\Interface\LoggerInterface;
use Core\Exception\ConfigException;
use Exception;

class Config implements ConfigInterface
{
    /**
     * @var Config|null
     */
    private static ?Config $instance = null;

    /**
     * @var array
     */
    private static array $config;

    /**
     * @var LoggerInterface
     */
    private static LoggerInterface $logger;

    /**
     * @param  LoggerInterface|null $logger
     */
    private function __construct(LoggerInterface $logger = null)
    {
        if ($logger)
            self::$logger = $logger;
        $this->loadEnv();
    }

    /**
     * @param  LoggerInterface|null $logger
     * @return Config
     */
    public static function load(LoggerInterface $logger = null): Config
    {
        if (!self::$instance)
            self::$instance = new self($logger);
        else if ($logger && !self::$logger)
            self::$logger = $logger;
        return self::$instance;
    }

    /**
     * Loads config files from /config/*.php
     *
     * The function assumes config files location in root folder.
     * Some config files use `env()` from `/core/Helpers.php`,
     * so we require it to avoid errors
     * @return void
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
     * @param  string      $key
     * @param  string|null $default
     * @return mixed
     */
    public static function get(string $key, string $default = null): mixed
    {
        if (!self::$instance)
            self::load();
        $path = explode('.', $key);
        $currConf = null;
        try {
            foreach ($path as $i => $k) {
                if ($i === 0) {
                    $currConf = self::$config[$k] ?? $default ?? throw new ConfigException($key);
                    continue;
                }
                $currConf = $currConf[$k] ?? $default ?? throw new ConfigException($key);
            }
        } catch (ConfigException $e) {
            self::log($e);
        }
        return $currConf;
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        if (!self::$config)
            self::load();
        return self::$config;
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
