<?php

declare(strict_types=1);

namespace Core\Interface;

interface ConfigInterface
{
    public static function get(string $key, string $default): mixed;


    public static function getAll(): array;
}
