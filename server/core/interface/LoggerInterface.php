<?php

namespace Core\Interface;

use Exception;

interface LoggerInterface
{
    public static function log(string|Exception $message): void;
}
