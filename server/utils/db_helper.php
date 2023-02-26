<?php

declare(strict_types=1);

use Core\Config;
use Core\DB;
use Core\Logger;

function dbConfig($connection = 'mysql')
{
    $connConfig = config("database.connections.{$connection}");
    $dbSettings = [
        'db' => config('database.default'),
        'dbhost' => $connConfig['host'],
        'dbport' => $connConfig['port'],
        'dbname' => $connConfig['database'],
        'username' => $connConfig['username'],
        'password' => $connConfig['password'],
        'charset' => $connConfig['charset'],
    ];

    return $dbSettings;
}

(function ($dbConfig, $logger) {
    DB::load($dbConfig, $logger);
})(dbConfig(), new Logger());
