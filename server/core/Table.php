<?php

declare(strict_types=1);

namespace Core;

use Core\Interface\TableInterface;

class Table implements TableInterface
{
    public static string $select = '*';


    public static string $from = '';


    public static string $where = '';


    public static ?string $options = null;


    public static array $orderBy = [null, 'DESC'];


    public static string $fields = '';


    public static ?string $constraints = null;


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


    public static function alter(string $tname, string $commands): string
    {
        $query = "ALTER TABLE `{$tname}` ";
        $query .= "{$commands}";
        $query = trimQuery($query);
        return $query;
    }


    public static function drop(string $tname): string
    {
        $query = "DROP TABLE IF EXISTS `{$tname}`;";
        $query = trimQuery($query);
        return $query;
    }


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


    public static function delete(string $tname): string
    {
        [$where, $options] = self::injectDelete();
        self::default();
        $query = "DELETE FROM {$tname} ";
        $query .= $where ? "WHERE {$where} " : '';
        $query .= $options ?? '';
        $query = trimQuery($query);
        return $query;
    }


    public static function default(): void
    {
        self::$select = '*';
        self::$from = '';
        self::$where = '';
        self::$options = null;
        self::$orderBy = [null, 'DESC'];
    }


    private static function injectCreate(): array
    {
        return [self::$fields, self::$constraints, self::$options];
    }


    private static function injectSelect(): array
    {
        return [self::$select, self::$from, self::$where, self::$options, self::$orderBy];
    }


    private static function injectUpdate(): array
    {
        return [self::$where, self::$options];
    }


    private static function injectDelete(): array
    {
        return [self::$where, self::$options];
    }
}
