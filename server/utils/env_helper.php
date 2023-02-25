<?php

declare(strict_types=1);

namespace Utils\EnvLoader;

use Core\Env;
use Core\Logger;

$env = Env::load(new Logger());

// $enValidation = function ($dotenv) {
//     $dotenv->required(['DB_USERNAME', 'DB_PASSWORD'])->notEmpty();
// };

// $env->validation($enValidation);
