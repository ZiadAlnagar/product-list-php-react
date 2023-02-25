<?php

declare(strict_types=1);

use App\Models\Book;
use App\Models\DVD;
use App\Models\Furniture;

function sku(): string
{
    $min = 10000000;
    $max = 99999999;
    return (string) rand($min, $max);
}

new DVD(sku(), 'dvd 1', 10, 4700);
new DVD(sku(), 'dvd 2', 11, 8500);
new DVD(sku(), 'dvd 3', 12, 5600);

new Book(sku(), 'book 1', 100, 2);
new Book(sku(), 'book 2', 130, 7.1);
new Book(sku(), 'book 3', 70, 3.75);

new Furniture(sku(), 'furniture 1', 1, '100x100x100');
new Furniture(sku(), 'furniture 2', 1, '120x170.5x100');
new Furniture(sku(), 'furniture 3', 1, '25x90x100.6');
