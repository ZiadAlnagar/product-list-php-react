<?php

declare(strict_types=1);

namespace App\Models;

use Core\DB;
use Core\Interface\LoggerInterface;
use Core\Table;
use Exception;

abstract class Product
{
    protected static DB $db;


    protected static LoggerInterface $logger;


    protected static Table  $tbl;


    protected static string $tname = 'products';


    protected static array $types = [
        'book' => 0,
        'dvd' => 1,
        'furniture' => 2,
    ];


    protected array $colnames = ['SKU', 'name', 'price', 'type', 'attribute'];


    protected int $id;


    protected string $sku;


    protected string $name;


    protected float $price;


    protected int $type;


    protected string $attribute;


    /**
     * @param string|int|float $attribute
     */
    public function __construct(
        string $sku,
        string $name,
        float $price,
        $attribute
    ) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->attribute = (string) $attribute;
        $this->create();
    }


    public function __invoke(): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'type' => $this->type,
            'attribute' => $this->attribute,
        ];
    }

    /**
     * @return Product
     * @param string|int|float $attribute
     */
    public static function save(
        string $sku,
        string $name,
        float $price,
        int $type,
        $attribute
    ) {
        $classType = ucfirst(array_search($type, self::$types, true));
        $class = __NAMESPACE__ . '\\' . $classType;
        return new $class($sku, $name, $price, $attribute);
    }


    public static function find(array $bind = null): array
    {
        self::checkDependencies();
        self::$tbl::$from = self::$tname;
        $query = self::$tbl->select();
        return self::$db->query($query, $bind);
    }


    public static function findById(int $id): ?array
    {
        self::$tbl::$where = 'id = :id';
        self::$tbl::$options = 'LIMIT :limit';
        $product = self::find([
            ':id' => $id,
            ':limit' => 1,
        ]);
        if (isset($product[0])) {
            return $product[0];
        }
        return null;
    }

    /**
     * @param  int   $sku
     */
    public static function findBySku(string $sku): ?array
    {
        self::$tbl::$where = 'sku = :sku';
        self::$tbl::$options = 'LIMIT :limit';
        $product = self::find([
            ':sku' => $sku,
            ':limit' => 1,
        ]);
        if (isset($product[0])) {
            return $product[0];
        }
        return null;
    }


    public static function findAll(): array
    {
        return self::find();
    }


    public static function findAllByType(string $type, array $bind = []): array
    {
        $type = self::getType($type);
        self::setWhere('type = :type');
        return self::find(array_merge([':type' => $type], $bind));
    }

    public static function update()
    {
    }

    public static function updateOne()
    {
    }


    public static function remove(array $bind): int
    {
        $query = self::$tbl::delete(self::$tname);
        return self::$db->query($query, $bind);
    }


    public static function removeOne(int $id): int
    {
        self::setWhere('id = :id');
        return self::remove([
            ':id' => $id,
        ]);
    }


    public static function loadDependencies(DB $db, LoggerInterface $logger): void
    {
        self::setDB($db);
        if (! isset(self::$logger)) {
            self::$logger = $logger;
        }
    }


    public static function setSelect(string $select = '*')
    {
        self::$tbl::$select = $select;
    }


    public static function setWhere(string $where = null)
    {
        self::$tbl::$where = $where;
    }


    public static function setOptions(string $options = null)
    {
        self::$tbl::$options = $options;
    }


    public static function setOrderBy(string $colname, string $by = 'DESC')
    {
        self::$tbl::$orderBy = [$colname, $by];
    }


    private function create(): void
    {
        [$query, $placeholders] = self::$tbl->insert(self::$tname, $this->colnames);
        $placeholders = explode(', ', $placeholders);
        $bindings = array_combine(
            $placeholders,
            [$this->sku, $this->name, $this->price, $this->type, $this->attribute]
        );
        self::$db->query($query, $bindings);
        $this->id = (int) self::$db->lastInsertId();
    }


    private static function setDB(DB $db)
    {
        if (! isset(self::$db)) {
            self::$db = $db;
            self::$tbl = $db::$table;
        }
    }


    private static function getType(string $type): ?int
    {
        $type = strtolower($type);
        try {
            if (! isset(self::$types[$type])) {
                throw new Exception("Product type `{$type}` doesn't exist.");
            }
        } catch (Exception $e) {
            self::log($e);
        }
        return self::$types[$type] ?? null;
    }


    /**
     * @param \Exception|string $message
     */
    private static function log($message): void
    {
        self::$logger::log($message);
    }


    private static function checkDependencies(): void
    {
        $deps = ['Core\DB', 'Core\Interface\LoggerInterface'];
        $currDeps = [self::$db ?? null, self::$logger ?? null];
        $missing = [];
        foreach ($deps as $i => $dep) {
            foreach ($currDeps as $j => $curr) {
                if (is_a($curr, $dep)) {
                    break;
                }
                if ($j === count($deps) - 1) {
                    $missing[] = $dep;
                }
            }
        }
        $message = '<br>';
        foreach ($missing as $v) {
            if ($v) {
                $message .= $v . '<br>';
            }
        }
        try {
            if ($message !== '<br>') {
                throw new Exception("Missing dependencies:{$message}");
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
