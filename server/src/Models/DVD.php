<?php

declare(strict_types=1);

namespace App\Models;

class DVD extends Product
{
    public function __construct(string $sku, string $name, float $price, int $attribute)
    {
        $this->type = self::$types['dvd'];
        parent::__construct($sku, $name, $price, $attribute);
    }
}
