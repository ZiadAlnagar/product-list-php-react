<?php

declare(strict_types=1);

namespace App\Models;

use Core\DB;
use Core\Interface\LoggerInterface;
use Core\Table;
use Exception;

abstract class Product
{
    /**
     * @var DB
     */
    protected static DB $db;

    /**
     * @var LoggerInterface
     */
    protected static LoggerInterface $logger;

    /**
     * @var Table
     */
    protected static Table  $tbl;

    /**
     * @var string
     */
    protected static string $tname = 'products';

    /**
     * @var array
     */
    protected static array $types = [
        'book' => 0,
        'dvd' => 1,
        'furniture' => 2,
    ];

    /**
     * @var array
     */
    protected array $colnames = ["SKU", "name", "price", "type", "attribute"];

    /**
     * @var int
     */
    protected int $id;

    /**
     * @var string
     */
    protected string $sku;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var float
     */
    protected float $price;

    /**
     * @var int
     */
    protected int $type;

    /**
     * @var string
     */
    protected string $attribute;

    /**
     * @param  string $name
     * @param  float  $price
     * @param  string $att
     */
    public function __construct(string $sku, string $name, float $price, string|int|float $attribute)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->attribute = (string) $attribute;
        $this->create();
    }

    /**
     * @return array
     */
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
     * @return void
     */
    private function create(): void
    {
        [$query, $placeholders] = self::$tbl->insert(self::$tname, $this->colnames);
        $placeholders = explode(', ', $placeholders);
        $bindings = array_combine(
            $placeholders,
            [
                $this->sku,
                $this->name,
                $this->price,
                $this->type,
                $this->attribute,
            ]
        );
        self::$db->query($query, $bindings);
        $this->id = (int) self::$db->lastInsertId();
    }

    /**
     * @param  string  $sku
     * @param  string  $name
     * @param  int     $type
     * @param  string|int|float  $attribute
     * @param  float   $price
     * @return Product
     */
    public static function save(string $sku, string $name, float $price, int $type, string|int|float $attribute)
    {
        $classType = ucfirst(array_search($type, self::$types));
        $class = __NAMESPACE__ . '\\' . $classType;
        return new $class($sku, $name, $price, $attribute);
    }

    /**
     * @param  array|null $bind
     * @return array
     */
    public static function find(array $bind = null): array
    {
        self::checkDependencies();
        self::$tbl::$from = self::$tname;
        $query = self::$tbl->select();
        return self::$db->query($query, $bind);
    }

    /**
     * @param  int   $id
     * @return array|null
     */
    public static function findById(int $id): array|null
    {
        self::$tbl::$where = "id = :id";
        self::$tbl::$options = "LIMIT :limit";
        $product = self::find([":id" => $id, ":limit" => 1]);
        if (isset($product[0]))
            return $product[0];
        return null;
    }

    /**
     * @param  int   $sku
     * @return array|null
     */
    public static function findBySku(string $sku): array|null
    {
        self::$tbl::$where = "sku = :sku";
        self::$tbl::$options = "LIMIT :limit";
        $product = self::find([":sku" => $sku, ":limit" => 1]);
        if (isset($product[0]))
            return $product[0];
        return null;
    }

    /**
     * @return array
     */
    public static function findAll(): array
    {
        return self::find();
    }

    /**
     * @param  string $type
     * @param  array  $bind
     * @return array
     */
    public static function findAllByType(string $type, array $bind = []): array
    {
        $type = self::getType($type);
        self::setWhere('type = :type');
        return self::find([
            ':type' => $type,
            ...$bind,
        ]);
    }

    public static function update()
    {
    }

    public static function updateOne()
    {
    }

    /**
     * @param  string $query
     * @param  array  $bind
     * @return int
     */
    public static function remove(array $bind): int
    {
        $query = self::$tbl::delete(self::$tname);
        return self::$db->query($query, $bind);
    }

    /**
     * @param  int  $id
     * @return int
     */
    public static function removeOne(int $id): int
    {
        self::setWhere("id = :id");
        return self::remove([":id" => $id]);
    }

    /**
     * @param  DB              $db
     * @param  LoggerInterface $logger
     * @return void
     */
    public static function loadDependencies(DB $db, LoggerInterface $logger): void
    {
        self::setDB($db);
        if (!isset(self::$logger))
            self::$logger = $logger;
    }

    /**
     * @param  DB   $db
     * @return void
     */
    private static function setDB(DB $db)
    {
        if (!isset(self::$db)) {
            self::$db = $db;
            self::$tbl = $db::$table;
        }
    }

    /**
     * @param  string $select
     * @return void
     */
    public static function setSelect(string $select = '*')
    {
        self::$tbl::$select = $select;
    }

    /**
     * @param  string|null $where
     * @return void
     */
    public static function setWhere(string $where = null)
    {
        self::$tbl::$where = $where;
    }

    /**
     * @param  string|null $options
     * @return void
     */
    public static function setOptions(string $options = null)
    {
        self::$tbl::$options = $options;
    }

    /**
     * @param  string $colname
     * @param  string $by
     * @return void
     */
    public static function setOrderBy(string $colname, string $by = 'DESC')
    {
        self::$tbl::$orderBy = [$colname, $by];
    }

    /**
     * @param  string   $type
     * @return int|null
     */
    private static function getType(string $type): int|null
    {
        $type = strtolower($type);
        try {
            if (!isset(self::$types[$type])) {
                throw new Exception("Product type `{$type}` doesn't exist.");
            }
        } catch (Exception $e) {
            self::log($e);
        }
        return self::$types[$type] ?? null;
    }

    /**
     * @param  Exception|string $message
     * @return void
     */
    private static function log(Exception|string $message): void
    {
        self::$logger::log($message);
    }

    /**
     * @return void
     */
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
