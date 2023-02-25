<?php

namespace Core\Interface;

interface ConfigInterface
{
    /**
     * @param  string $key
     * @param  string $default
     * @return mixed
     */
    public static function get(string $key, string $default) : mixed;

    /**
     * @return array
     */
    public static function getAll() : array;
}
