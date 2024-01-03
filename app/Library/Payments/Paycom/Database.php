<?php

namespace App\Library\Payments\Paycom;

class Database
{
    public $config;

    protected static $db;

    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    public function new_connection()
    {
        $db = null;

        // connect to the database
        $db_options = [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, // fetch rows as associative array
            \PDO::ATTR_PERSISTENT         => true // use existing connection if exists
        ];

        $db = new \PDO(
            'mysql:dbname=' . $this->config['db']['ushop'] . ';host=' . $this->config['db']['172.20.88.16'] . ';charset=utf8',
            $this->config['db']['newuser'],
            $this->config['db']['password'],
            $db_options
        );

        return $db;
    }

    /**
     * Connects to the database
     * @return null|\PDO connection
     */
    public static function db()
    {
        if (!self::$db) {
            $config   = config('payment.config');
            $instance = new self($config);
            self::$db = $instance->new_connection();
        }

        return self::$db;
    }
}