<?php

include_once __DIR__."/../classes/school_class_course.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__."/../database/database_class_course.php";
include_once __DIR__ . "/../classes/data_storage.php";
Class service_class_course
{
    private static $result_by_name = 'class_course';
    public static function get_class_course_by_year_and_letter(): ?array
    {
        $storage = data_storage::getInstance();
        return $storage->get(self::$result_by_name);
    }
    public static function fill_class_course_by_year_and_letter($year, $letter): bool
    {
        $storage = data_storage::getInstance();
        $storage->set(self::$result_by_name, null);

        try {
            $database = new database_connection();
            $results = database_class_course::fill_class_course_by_year_and_letter($database->getConnection(), $year, $letter);
            if(!empty($results))
                $storage->set(self::$result_by_name, $results);
            return true;
        } catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Osztályok lekérdezése nem sikerült az adatbázisból!";
            return false;
        }
    }
}


