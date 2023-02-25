<?php

declare(strict_types=1);

use Core\DB;

function productsTable()
{
    $productsFields = 'SKU VARCHAR(8) UNIQUE NOT NULL,
                    name VARCHAR(128) NOT NULL,
                    price FLOAT(2) NOT NULL,
                    type TINYINT(1) NOT NULL,
                    attribute VARCHAR(16) NOT NULL';
    $db = DB::load();
    $productsTable = $db::$table->create('products', $productsFields);
    $db->query($productsTable);
}


// $alterCommand = "MODIFY type TINYINT(1) NOT NULL,
//                 MODIFY attribute VARCHAR(10) NOT NULL,
//                 MODIFY price FLOAT(2) NOT NULL";
// $alter = $db::$table->alter("products", $alterCommand);
// $db->query($alter);
