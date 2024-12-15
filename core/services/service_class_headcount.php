<?php

include_once __DIR__."/../classes/school_class_headcount.php";
include_once __DIR__."/../database/database_class.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__ . "/../classes/data_storage.php";

Class service_class_headcount
{
    private static $result_by_name_max = 'class_headcount_max';
    private static $result_by_name_sum = 'class_headcount_sum';
    public static function get_class_max_headcount(): ?array
    {
        $storage = data_storage::getInstance();
        return $storage->get(self::$result_by_name_max);
    }
    public static function get_class_sum_headcount(): ?array
    {
        $storage = data_storage::getInstance();
        return $storage->get(self::$result_by_name_sum);
    }
    public static function fill_class_max_headcount_from_database($year, $letter): bool
    {
        $storage = data_storage::getInstance();
        $storage->set(self::$result_by_name_max, null);
        try {
            $database = new database_connection();
            $results = database_class::fill_class_max_headcount_from_database($database->getConnection(), $year, $letter);
            if(!empty($results))
                $storage->set(self::$result_by_name_max, $results);
            return true;
        } catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Maximális osztálylétszám lekérdezése nem sikerült az adatbázisból!";
            return false;
        }
    }

    public static function fill_class_sum_headcount_from_database($year, $letter): bool
    {
        $storage = data_storage::getInstance();
        $storage->set(self::$result_by_name_sum, null);
        try {
            $database = new database_connection();
            $results = database_class::fill_class_sum_headcount_from_database($database->getConnection(), $year, $letter);
            if(!empty($results))
                $storage->set(self::$result_by_name_sum, $results);
            return true;
        } catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Összesített osztálylétszám lekérdezése nem sikerült az adatbázisból!";
            return false;
        }
    }
}