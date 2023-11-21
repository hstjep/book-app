<?php

namespace App\Utils\Session;

class ConsoleSession implements SessionInterface
{
    private static $instance;
    private $values = [];

    private function __construct()
    { }

    /**
     * Gets instance
     *
     * @return ConsoleSession
     */
    public static function getInstance(): ConsoleSession
    {
        if (self::$instance == null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Gets the value by key
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->values[$key];
    }

    /**
     * Sets the value
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->values[$key] = $value;
    }
}
