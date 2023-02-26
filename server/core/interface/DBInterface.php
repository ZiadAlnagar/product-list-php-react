<?php

declare(strict_types=1);

namespace Core\Interface;

use Core\DB;
use Core\Table;

interface DBInterface
{
    public static function load(array $settings, LoggerInterface $logger, int $ins): DB;


    public function query(string $query, string $bind, int $fetchmode, bool $debug): array|int|null;


    public function lastInsertId(): string|false;


    public function table(): Table;
}
