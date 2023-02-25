<?php

namespace Core\Exception;

use OutOfBoundsException;

class ConfigException extends OutOfBoundsException
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
        Config '{$key}' is invalid.\n
        Recommended steps:\n
        - check if the path is correct\n
        - Add new config file in the 'config' folder or adjust an existant one.";
    }
}
