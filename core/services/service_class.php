<?php

include_once __DIR__."/../database/database_class.php";
include_once __DIR__."/../database/database_user.php";
include_once __DIR__."/../classes/school_class.php";
include_once __DIR__."/../database/database_connection.php";

Class service_class
{
    public static function class_validate($school_class): bool
    {
        $is_valid = service_class::class_validate_grade($school_class);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibás osztály év!";
            return false;
        }

        $is_valid = service_class::class_validate_letter($school_class);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibás osztály betűjel!";
            return false;
        }

        $is_valid = service_class::class_validate_headcount($school_class);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibás osztály létszám!";
            return false;
        }

        $is_valid = service_class::class_validate_start_year($school_class);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibás osztály kezdési év!";
            return false;
        }

        return true;
    }

    public static function class_validate_grade($school_class): bool
    {
        if (!$school_class->getYear()) return true;

        if ( 6 > $school_class->getYear() || $school_class->getYear() > 12)
            return false;

        return true;
    }

    public static function class_validate_letter($school_class): bool
    {
        if (!$school_class->getLetter()) return true;

        $valid_letters = ["a", "b", "c", "d", "e"];
        if (!in_array(strtolower($school_class->getLetter()), $valid_letters)) {
            return false;
        }

        return true;
    }

    public static function class_validate_headcount($school_class): bool
    {
        if (!$school_class->getHeadcount()) return true;

        if ( 1 > $school_class->getHeadcount() || $school_class->getHeadcount() > 40)
            return false;

        return true;
    }

    public static function class_validate_start_year($school_class): bool
    {
        if (!$school_class->getStartYear()) return true;

        if ( 2018 > (int)$school_class->getStartYear() || (int)$school_class->getStartYear() > date("Y"))
            return false;

        return true;
    }

    public static function class_add_to_database($school_class): ?bool
    {
        try{
            $database = new database_connection();

            $user_id = database_user::get_user_id_by_email($database->getConnection(), [$school_class->getHeadmasterEmail()] );
            if(empty($user_id)){
                $_SESSION["error"] = "Osztály hozzáadás nem sikerült az adatbázishoz!";
                return false;
            }

            $is_valid = database_class::add_to_database($database->getConnection(),
                $school_class->getYear(), $school_class->getLetter() ,$school_class->getHeadcount(),
                $school_class->getStartYear(), $user_id[0], $school_class->getDivision());

            if(!$is_valid){
                $_SESSION["error"] = "Osztály hozzáadás nem sikerült az adatbázishoz!";
                return false;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Osztály hozzáadás nem sikerült az adatbázishoz!";
            return false;}
    }

    public static function class_delete_from_database($school_class): bool
    {
        try{
            $database = new database_connection();
            database_class::delete_from_database($database->getConnection(), $school_class->getYear(), $school_class->getLetter());
            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Osztály törlése nem sikerült az adatbázisból!";
            return false;}
    }

    public static function class_modify_in_database($school_class): ?bool
    {
        try{
            $database = new database_connection();

            $user_id = database_user::get_user_id_by_email($database->getConnection(), [$school_class->getHeadmasterEmail()] );
            if(empty($user_id)){
                $_SESSION["error"] = "Osztály hozzáadás nem sikerült az adatbázishoz!";
                return false;
            }

            $is_valid = database_class::modify_in_database($database->getConnection(),
                $school_class->getYear(), $school_class->getLetter() ,$school_class->getHeadcount(),
                $school_class->getStartYear(), $user_id[0], $school_class->getDivision());

            if(!$is_valid){
                $_SESSION["error"] = "Osztály módosítása nem sikerült az adatbázisban!";
                return false;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Osztály módosítása nem sikerült az adatbázisban!";
            return false;}
    }

    public static function class_get_all(): ?array
    {
        try{
            $database = new database_connection();

            $classes = database_class::class_get_all($database->getConnection());

            $user_id_array = service_class::class_get_user_id($classes);
            $email_array = database_user::get_email_by_user_id($database->getConnection(), $user_id_array);
            $is_valid = service_class::class_fill_email_for_user($classes, $email_array);
            if(!$is_valid){
                $_SESSION["error"] = "Hibás adatbázis lekérdezés!";
                return null;
            }

            return $classes;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Hibás adatbázis lekérdezés!";
            return null;}
    }

    static function class_get_user_id($classes):array
    {
        $user_id_array = [];
        foreach ($classes as $class) {
            $user_id_array[]=$class->getHeadmasterEmail();}

        return $user_id_array;
    }

    static function class_fill_email_for_user($classes, $email_array): bool
    {
        if (count($classes) != count($email_array))
            return false;

        for ($i = 0; $i < count($classes) && $i < count($email_array); $i++) {
            $classes[$i]->setHeadmasterEmail($email_array[$i]);}

        return true;
    }
}

