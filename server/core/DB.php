<?php

declare(strict_types=1);

namespace Core;

use Core\Interface\LoggerInterface;
use Exception;
use PDO;
use PDOException;
use PDOStatement;

class DB
{
    public static Table $table;


    private static array $instances = [];


    private PDO $pdo;


    private PDOStatement $query;


    private bool $success = false;


    private LoggerInterface $logger;


    private function __construct(array $settings, LoggerInterface $logger)
    {
        $this->logger = $logger;
        self::$table = new Table();
        $this->connect(...$settings);
    }


    public static function load(
        array $settings = null,
        LoggerInterface $logger = null,
        int $ins = 0
    ): self {
        if (empty(self::$instances) && ! array_key_exists($ins, self::$instances)) {
            self::$instances[$ins] = new self($settings, $logger);
        }
        return self::$instances[$ins];
    }


    public function query(
        string $query,
        array $bind = null,
        int $fetchmode = PDO::FETCH_ASSOC,
        bool $debug = false
    ): array|int|null {
        $query = trim($query);
        $this->prepare($query, $debug);
        $this->success = $this->query->execute($bind);
        if (! $this->success) {
            $this->log('query faild' . $this->query->errorInfo());
        }
        $rawStatement = explode(' ', $query);
        $statement = strtolower($rawStatement[0]);
        if ($statement === 'select' || $statement === 'show') {
            return $this->query->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->query->rowCount();
        }
        return null;
    }


    public function lastInsertId(): string|false
    {
        return $this->pdo->lastInsertId();
    }


    public function table(): Table
    {
        return self::$table;
    }

    /**
     * Tries to connect to database `$dbname` while establishing a connection to db driver. If not found exception is
thrown
     * and it tries to establish a connection first, then query: create database `$dbname`, use `$dbname`.
     */
    private function connect(
        string $db,
        string $dbhost,
        string $dbport,
        string $dbname,
        string $username,
        string $password,
        string $charset
    ): void {
        $dsn = "{$db}:" . "host={$dbhost};port={$dbport};dbname={$dbname};charset={$charset}";
        try {
            $this->pdo = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            try {
                $dsn = "{$db}:" . "host={$dbhost};port={$dbport};charset={$charset}";
                $this->pdo = new PDO($dsn, $username, $password);
                $createDBQuery = "CREATE DATABASE IF NOT EXISTS {$dbname};
                                USE {$dbname};";
                $this->query($createDBQuery);
            } catch (PDOException $ex) {
                $this->log("Could not connect to the database {$dbname}");
                $this->log($ex);
                die();
            }
        }
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }


    private function prepare(string $query, bool $debug): void
    {
        try {
            $this->query = $this->pdo->prepare($query);
            if ($debug) {
                $this->query->debugDumpParams();
            }
        } catch (PDOException $e) {
            $this->log($e);
            $this->log($query);
        }
    }


    private function log(string|Exception $message): void
    {
        if (! $this->logger) {
        } else {
            $this->logger->log($message);
        }
    }
}
