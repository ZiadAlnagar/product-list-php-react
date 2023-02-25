<?php

declare(strict_types=1);

namespace App\Models;

class Furniture extends Product
{
    public function __construct(string $sku, string $name, float $price, string $attribute)
    {
        $this->type = self::$types['furniture'];
        parent::__construct($sku, $name, $price, $attribute);
    }
}
