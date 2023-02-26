<?php

declare(strict_types=1);

namespace Core\Interface;

use Exception;

interface LoggerInterface
{
    /**
     * @param string|\Exception $message
     */
    public static function log($message): void;
}
