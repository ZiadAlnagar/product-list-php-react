<?php

declare(strict_types=1);

namespace App\Middlewares;

class ErrorMiddleware
{
    public function __invoke(string $error)
    {
        if ($error === 'CastError') {
            // http_response_code(400)
            // $message['status_code_header'] = ''
            // $message =  response.status(400).send({ error: 'malformatted id' });
        }
    }
}
