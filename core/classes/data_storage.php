<?php

if (session_status() === PHP_SESSION_NONE) {session_start();}

class data_storage
{
    private static $instance = null;

    private $data = [];
    private function __construct() {}

    public static function getInstance(): self {

        if(self::$instance === null)
        {
            self::$instance = new data_storage();
            if (isset($_SESSION['data_storage'])) {
                self::$instance = unserialize($_SESSION['data_storage']);
            }
        }

        return self::$instance;
    }

    public function set(string $key, $value): void {
        $this->data[$key] = $value;
        $_SESSION['data_storage'] = serialize($this);
    }

    public function get(string $key) {
        return $this->data[$key] ?? null;
    }

}