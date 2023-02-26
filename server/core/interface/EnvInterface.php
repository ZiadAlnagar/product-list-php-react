<?php

declare(strict_types=1);

namespace Core\Interface;

use Core\Env;
use Dotenv\Dotenv;

interface EnvInterface
{
    public static function load(LoggerInterface $logger = null): Env;

    public static function get(string $key, string $default): string;

    public function getDotEnv(): Dotenv;
}
