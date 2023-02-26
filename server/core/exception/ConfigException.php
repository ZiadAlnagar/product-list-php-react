<?php

declare(strict_types=1);

namespace Core\Exception;

use OutOfBoundsException;

class ConfigException extends OutOfBoundsException
{
    public function __construct(string $key)
    {
        parent::__construct($this->message($key));
    }


    private function message(string $key): string
    {
        return "
        Config '{$key}' is invalid.\n
        Recommended steps:\n
        - check if the path is correct\n
        - Add new config file in the 'config' folder or adjust an existant one.";
    }
}
