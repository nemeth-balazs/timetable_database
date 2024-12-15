<?php

include_once __DIR__."/../database/database_course.php";
include_once __DIR__."/../database/database_class_course.php";
include_once __DIR__."/../database/database_teacher_course.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__."/../database/database_user.php";
include_once __DIR__."/../classes/course.php";

Class service_course
{
    public static function course_validate($course): bool
    {

        $is_valid = service_course::course_validate_day($course);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibásan megadott nap!";
            return false;
        }

        $is_valid = service_course::course_validate_start($course);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibásan időpont!";
            return false;
        }

        return true;
    }

    public static function course_validate_day($course): bool
    {
        if (!$course->getDay()) return true;

        $valid_days = ["hetfő", "kedd", "szerda", "csütörtök", "péntek"];
        if (!in_array(strtolower($course->getDay()), $valid_days)) {
            return false;}

        return true;
    }

    public static function course_validate_start($course): bool
    {
        if (!$course->getStart()) return true;

        $pattern = '/^(?:[01]?[0-9]|2[0-3]):([0-5]?[0-9])(?::([0-5]?[0-9]))?$/';
        if (!preg_match($pattern, $course->getStart())) {
            return false;}

        return true;
    }

    public static function course_add_to_database($course): ?bool
    {
        try{
            $database = new database_connection();

            $is_valid = database_course::add_to_database($database->getConnection(),
                $course->getRoomNumber(), $course->getDay(), $course->getStart(), $course->getSubjectName());

            if(!$is_valid){
                $_SESSION["error"] = "Tanóra hozzáadása nem sikerült az adatbázishoz!";
                return false;
            }

            list($year_array, $letter_array) = service_course::get_year_and_letter_array($course->getClass_array());
            $is_valid = database_class_course::add_to_database($database->getConnection(),
                $course->getRoomNumber(), $course->getDay(), $course->getStart(),
                $year_array, $letter_array);

            if(!$is_valid){
                $_SESSION["error"] = "Tanóra hozzáadása nem sikerült az adatbázishoz!";
                return false;
            }

            $user_array = database_user::get_user_id_by_email($database->getConnection(), $course->getTeacherEmail_array() );
            if(empty($user_array)){
                $_SESSION["error"] = "Tanóra hozzáadása nem sikerült az adatbázishoz!";
                return false;
            }

            $is_valid = database_teacher_course::add_to_database($database->getConnection(),
                $course->getRoomNumber(), $course->getDay(), $course->getStart(),
                $user_array);

            if(!$is_valid){
                $_SESSION["error"] = "Tanóra hozzáadása nem sikerült az adatbázishoz!";
                return false;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Tanóra hozzáadása nem sikerült az adatbázishoz!";
            return false;
        }
    }

    public static function course_delete_from_database($course): bool
    {
        try{
            $database = new database_connection();
            database_course::delete_from_database($database->getConnection(), $course->getRoomNumber(), $course->getDay(), $course->getStart());
            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Tanóra törlése nem sikerült az adatbázisból!";
            return false;
        }
    }

    public static function course_modify_in_database($course): bool
    {
        try{
            $database = new database_connection();

            $is_valid = database_course::modify_in_database($database->getConnection(), $course->getRoomNumber(), $course->getDay(), $course->getStart(), $course->getSubjectName());
            if(!$is_valid){
                $_SESSION["error"] = "Tanóra módosítása nem sikerült az adatbázisban!";
                return false;
            }

            list($year_array, $letter_array) = service_course::get_year_and_letter_array($course->getClass_array());
            $is_valid = database_class_course::modify_in_database($database->getConnection(), $course->getRoomNumber(), $course->getDay(), $course->getStart(), $year_array, $letter_array);
            if(!$is_valid){
                $_SESSION["error"] = "Tanóra módosítása nem sikerült az adatbázisban!";
                return false;
            }

            $user_array = database_user::get_user_id_by_email($database->getConnection(), $course->getTeacherEmail_array());
            if(empty($user_array)){
                $_SESSION["error"] = "Tanóra módosítása nem sikerült az adatbázisban!";
                return false;
            }

            $is_valid = database_teacher_course::modify_in_database($database->getConnection(), $course->getRoomNumber(), $course->getDay(), $course->getStart(), $user_array);
            if(!$is_valid){
                $_SESSION["error"] = "Tanóra módosítása nem sikerült az adatbázisban!";
                return false;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Tanóra módosítása nem sikerült az adatbázisban!";
            return false;
        }
    }

    public static function get_year_and_letter_array($class_array): ?array
    {
        $year_array = [];
        $letter_array = [];
        foreach ($class_array as $class) {
           if(empty($class)) continue;

            $year_array[] = substr($class, 0, -1);
             $letter_array[] = substr($class, -1);
        }

        return [$year_array, $letter_array];
    }

    public static function course_get_all(): ?array
    {
        try{
            $database = new database_connection();

            $courses = database_course::course_get_all($database->getConnection());
            database_teacher_course::course_fill_user_id_for_course($database->getConnection(), $courses);
            database_class_course::course_fill_class_for_course($database->getConnection(), $courses);

            $user_id_array = service_course::course_get_user_id($courses);
            $email_array = database_user::get_email_by_user_id($database->getConnection(), $user_id_array);
            $is_valid = service_course::course_fill_email_for_user($courses, $user_id_array, $email_array);
            if(!$is_valid){
                $_SESSION["error"] = "Hibás adatbázis lekérdezés!";
                return null;
            }

            return $courses;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Hibás adatbázis lekérdezés!";
            return null;
        }
    }

    static function course_get_user_id($courses): array
    {
        $user_id_array = [];
        foreach ($courses as $course) {
            $user_id_array = array_merge($user_id_array, $course->getTeacherEmail_array());
        }

        return array_unique($user_id_array);
    }
    static function course_fill_email_for_user($courses, $user_id_array, $email_array):bool
    {
        if (count($user_id_array) != count($email_array))
            return false;

        $user_map = array_combine($user_id_array, $email_array);
        foreach ($courses as $course) {

            foreach ($course->getTeacherEmail_array_to_modify() as &$email) {
                if (isset($user_map[$email])) {
                    $email = $user_map[$email];
                }
            }
            unset($email);
        }

        return true;
    }
}

