<?php

namespace App\Middleware;

class UnknownEndpoint
{
    public function __invoke()
    {
        http_response_code(404);
    }
}
