<?php

declare(strict_types=1);

namespace Core;

use Core\Interface\TableInterface;

class Table implements TableInterface
{
    /**
     * @var string
     */
    public static string $select = '*';

    /**
     * @var string
     */
    public static string $from = '';

    /**
     * @var string
     */
    public static string $where = '';

    /**
     * @var string|null
     */
    public static ?string $options = null;

    /**
     * @var array
     */
    public static array $orderBy = [null, 'DESC'];

    /**
     * @var string
     */
    public static string $fields = '';

    /**
     * @var string|null
     */
    public static ?string $constraints = null;

    /**
     * @param  string $tname
     * @return string
     */
    public static function create(string $tname): string
    {
        [$fields, $constraints, $options] = self::injectCreate();
        self::default();
        $query = "CREATE TABLE IF NOT EXISTS `{$tname}` (";
        $query .= 'id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY';
        $query .= $fields ? ", {$fields}" : '';
        $query .= $constraints ?? '';
        $query .= ') ';
        $query .= $options ?? '';
        $query = trimQuery($query);
        return $query;
    }

    /**
     * @param  string $tname
     * @param  string $commands
     * @return string
     */
    public static function alter(string $tname, string $commands): string
    {
        $query = "ALTER TABLE `{$tname}` ";
        $query .= "{$commands}";
        $query = trimQuery($query);
        return $query;
    }

    /**
     * @param  string $tname
     * @return string
     */
    public static function drop(string $tname): string
    {
        $query = "DROP TABLE IF EXISTS `{$tname}`;";
        $query = trimQuery($query);
        return $query;
    }

    /**
     * @return string
     */
    public static function select(): string
    {
        [$select, $from, $where, $options, $orderBy] = self::injectSelect();
        self::default();
        $query = "SELECT {$select} FROM `{$from}` ";
        $query .= $where ? "WHERE {$where} " : '';
        $query .= $orderBy[0] ? "ORDER BY :{$orderBy[0]} :{$orderBy[1]} " : '';
        $query .= $options ?? '';
        $query = trimQuery($query);
        return $query;
    }

    /**
     * @param  string $tname
     * @param  array  $colnames
     * @return array
     */
    public static function insert(string $tname, array $colnames): array
    {
        self::default();
        $placeHolders = genPlaceHolders($colnames);
        $query = "INSERT INTO `{$tname}` ";
        $query .= '(' . implode(', ', $colnames) . ') ';
        $query .= 'VALUES (' . $placeHolders . ')';
        $query = trimQuery($query);
        return [$query, $placeHolders];
    }

    /**
     * @param  string $tname
     * @param  array $colnames
     * @return array
     */
    public static function update(string $tname, array $colnames): array
    {
        [$where, $options] = self::injectUpdate();
        self::default();
        $placeHolders = genPlaceHolders($colnames, '? = :?');
        $query = "UPDATE `{$tname}` ";
        $query .= "SET {$placeHolders} ";
        $query .= $where ? "WHERE {$where} " : '';
        $query .= $options ?? '';
        $query = trimQuery($query);
        return [$query, $placeHolders];
    }

    /**
     * @param  string $tname
     * @return string
     */
    public static function delete(string $tname): string
    {
        [$where, $options] = self::injectDelete();
        self::default();
        $query = "DELETE FROM $tname ";
        $query .= $where ? "WHERE $where " : '';
        $query .= $options ?? '';
        $query = trimQuery($query);
        return $query;
    }

    /**
     * @return void
     */
    public static function default(): void
    {
        self::$select = '*';
        self::$from = '';
        self::$where = '';
        self::$options = null;
        self::$orderBy = [null, 'DESC'];
    }

    /**
     * @return array
     */
    private static function injectCreate(): array
    {
        return [self::$fields, self::$constraints, self::$options];
    }

    /**
     * @return array
     */
    private static function injectSelect(): array
    {
        return [self::$select, self::$from, self::$where, self::$options, self::$orderBy];
    }

    /**
     * @return array
     */
    private static function injectUpdate(): array
    {
        return [self::$where, self::$options];
    }

    /**
     * @return array
     */
    private static function injectDelete(): array
    {
        return [self::$where, self::$options];
    }
}
