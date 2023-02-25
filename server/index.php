<?php

declare(strict_types=1);

require 'bootstrap.php';

use Core\Config;
use Core\DB;
use Core\Env;
use Core\Logger;
use App\Models\Product;
use App\Controllers\ProductController;
// use App\Middleware\ErrorMiddleware;
use App\Middleware\UnknownEndpoint;

$env = Env::load(new Logger());
$config = Config::load(new Logger());

require './utils/db_helper.php';
require './database/products_table.php';

Product::loadDependencies(DB::load(), new Logger());

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$unknowEndpoint = new UnknownEndpoint();

if ($uri[1] === 'api' && $uri[2] === 'products') {
    $subRoute = null;
    $subValue = null;
    $productId = null;
    if (isset($uri[3])) {
        if ($uri[3] === 'sku') {
            $subRoute = 'sku';
            if ($uri[4])
                $subValue = $uri[4];
        } else $productId = (int) $uri[3];
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];

    $controller = new ProductController($requestMethod, $productId, $subRoute, $subValue);
    try {
        $controller->processRequest();
    } catch (Exception $e) {
    }
} else {
    $unknowEndpoint();
    exit();
}

// require './database/factory.php';
