<?php

include_once __DIR__."/service_user.php";
include_once __DIR__."/../classes/subject_number.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__ . "/../classes/data_storage.php";
Class service_subject_number
{
    private static $result_by_name = 'subject_number';
    public static function get_subject_number_by_teacher(): ?array{
        $storage = data_storage::getInstance();
        return $storage->get(self::$result_by_name);
    }
    public static function fill_subject_number_from_database($user_email): bool
    {
        $storage = data_storage::getInstance();
        $storage->set(self::$result_by_name, null);
        try{
            $database = new database_connection();

            $user_id = null;
            if(!empty($user_email)){
                $user_id = service_user::get_user_id_by_email($database->getConnection(), $user_email);
                if(!$user_id){return false;}
            }

            $results = database_subject::fill_subject_number_from_database($database->getConnection(), $user_id);
            if(!empty($results))
                $storage->set(self::$result_by_name, $results);
            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Tantárgyak összesítése nem sikerült az adatbázisból!";
            return false;
        }
    }
}