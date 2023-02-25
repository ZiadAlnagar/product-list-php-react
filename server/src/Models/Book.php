<?php

declare(strict_types=1);

namespace App\Models;

class Book extends Product
{
    public function __construct(string $sku, string $name, float $price, float $attribute)
    {
        $this->type = self::$types['book'];
        parent::__construct($sku, $name, $price, $attribute);
    }
}
