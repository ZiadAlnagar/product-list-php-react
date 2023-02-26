<?php

declare(strict_types=1);

namespace Core\Interface;

interface ConfigInterface
{
    /**
     * @return mixed
     */
    public static function get(string $key, string $default);


    public static function getAll(): array;
}
