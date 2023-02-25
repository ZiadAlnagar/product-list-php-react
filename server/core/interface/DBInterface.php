<?php

namespace Core\Interface;

use Core\DB;
use Core\Table;

interface DBInterface
{
    /**
     * @param  array           $settings
     * @param  LoggerInterface $logger
     * @param  int             $ins
     * @return DB
     */
    public static function load(array $settings, LoggerInterface $logger, int $ins): DB;

    /**
     * @param  string         $query
     * @param  string         $bind
     * @param  int            $fetchmode
     * @param  bool           $debug
     * @return array|int|null
     */
    public function query(string $query, string $bind, int $fetchmode, bool $debug): array|int|null;

    /**
     * @return string|false
     */
    public function lastInsertId(): string|false;

    /**
     * @return Table
     */
    public function table(): Table;
}
