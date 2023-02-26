<?php

declare(strict_types=1);

namespace App\Middleware;

class UnknownEndpoint
{
    public function __invoke()
    {
        http_response_code(404);
    }
}
