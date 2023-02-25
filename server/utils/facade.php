<?php

declare(strict_types=1);

$files = glob(__DIR__ . '/*.{php}', GLOB_BRACE);

foreach ($files as $file) {
    $curr = basename($file, '.php');
    if ($curr !== 'facade' || $curr !== '') {
        require $file;
    }
}
