<?php

declare(strict_types=1);

namespace Core\Interface;

interface TableInterface
{
    public static function create(string $tname): string;

    public static function alter(string $tname, string $commands): string;

    public static function drop(string $tname): string;

    public static function select(): string;

    public static function insert(string $tname, array $colnames): array;

    public static function update(string $tname, array $colnames): array;

    public static function delete(string $tname): string;
}
