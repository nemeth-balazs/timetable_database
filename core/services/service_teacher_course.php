<?php

if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__."/service_user.php";
include_once __DIR__."/../classes/teacher_course.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__."/../database/database_teacher_course.php";
include_once __DIR__."/../database/database_user.php";
include_once __DIR__ . "/../classes/data_storage.php";

Class service_teacher_course
{
    private static $result_by_name = 'teacher_courses';
    public static function get_teacher_course_by_user_name(): ?array{
        $storage = data_storage::getInstance();
        return $storage->get(self::$result_by_name);
    }
    public static function fill_teacher_course_from_database($user_email): bool
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

            $results = database_teacher_course::fill_teacher_course_from_database($database->getConnection(), $user_id);
            if(!empty($results))
                $storage->set(self::$result_by_name, $results);
            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Az órarend lekérdezése nem sikerült az adatbázisból!";
            return false;
        }
    }


}