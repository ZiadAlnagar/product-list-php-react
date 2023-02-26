<?php

declare(strict_types=1);

namespace Core\Interface;

use Exception;

interface LoggerInterface
{
    public static function log(string|Exception $message): void;
}
