<?php

namespace Core\Exception;

use OutOfBoundsException;

class EnvException extends OutOfBoundsException
{
    /**
     * @param  string $key
     */
    public function __construct(string $key)
    {
        parent::__construct($this->message($key));
    }

    /**
     * @param  string $key
     * @return string
     */
    private function message(string $key): string
    {
        return "
        Env '{$key}' is invalid and no default value provided.\n
        Recommended steps:\n
        - check if env key is spelled correctly and has valid value\n
        - provide default value.";
    }
}
