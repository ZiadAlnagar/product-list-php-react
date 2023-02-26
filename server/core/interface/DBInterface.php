<?php

declare(strict_types=1);

namespace Core\Interface;

use Core\DB;
use Core\Table;

interface DBInterface
{
    public static function load(array $settings, LoggerInterface $logger, int $ins): DB;


    /**
     * @return mixed[]|int|null
     */
    public function query(string $query, string $bind, int $fetchmode, bool $debug);


    /**
     * @return string|true
     */
    public function lastInsertId();


    public function table(): Table;
}
