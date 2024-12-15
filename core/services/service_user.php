<?php

include_once __DIR__."/../database/database_user.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__."/../classes/user.php";
include_once __DIR__."/service_common.php";

Class service_user
{
    public static function user_validate($user): bool
    {
        $is_valid = service_user::user_validate_password($user);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibás jelszó!";
            return false;
        }

        $is_valid = service_user::user_validate_email($user);
        if(!$is_valid) {
            $_SESSION["error"] = "Nem megfelelő email cím!";
            return false;
        }

        $is_valid = service_user::user_validate_phone($user);
        if(!$is_valid){
            $_SESSION["error"] = "Nem megfelelő telefonszám!";
            return false;
        }

        return true;
    }

    public static function user_validate_password($user): bool
    {
        $password1 = $user->getUserPassword_1();
        $password2 = $user->getUserPassword_2();

        if (!$password1 && !$password2) return true;

        $uppercase      = preg_match('@[A-Z]@', $password1);
        $lowercase      = preg_match('@[a-z]@', $password1);
        $number         = preg_match('@[0-9]@', $password1);
        $specialChars   = preg_match('@[^\w]@', $password1);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password1) < 8)
            return false;

        if ($password1 !== $password2)
            return false;

        return true;
    }

    public static function user_validate_email($user): bool
    {
        if (!$user->getUserEmail()) return true;

        if (!filter_var($user->getUserEmail(), FILTER_VALIDATE_EMAIL))
            return false;

        return true;
    }

    public static function user_validate_phone($user): bool
    {
        if (!$user->getUserPhone()) return true;

        $pattern = '/^\+36\d{9}$/';
        if (!preg_match($pattern, $user->getUserPhone()))
            return false;

        return true;
    }

    public static function user_add_to_database($user): ?bool
    {
        try{
            $database = new database_connection();

            $is_valid = database_user::add_to_database($database->getConnection(),
                $user->getUserId(), password_hash($user->getUserPassword_1(), PASSWORD_DEFAULT) ,$user->getUserPhone(),
                $user->getUserEmail(), $user->getUserLevel(), $user->getUserName());

            if(!$is_valid) {
                $_SESSION["error"] = "Felhasználó hozzáadás nem sikerült az adatbázishoz!";
                return $is_valid;
            }

            if (!$user->getUserSubjects()) return $is_valid;

            $subjects_array = service_common::prepare_array_items($user->getUserSubjects());
            $is_valid = database_subject::add_to_database($database->getConnection(), $user->getUserId(), $subjects_array);
            if(!$is_valid){
                $_SESSION["error"] = "Felhasználó hozzáadás nem sikerült az adatbázishoz!";
                return false;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Felhasználó hozzáadás nem sikerült az adatbázishoz!";
            return false;
        }
    }

    public static function user_create_new_account_add_to_database($user)
    {
        try{
            $database = new database_connection();
            $is_valid = database_user::create_new_account_add_to_database($database->getConnection(),
                $user->getUserId(), password_hash($user->getUserPassword_1(), PASSWORD_DEFAULT));

            if(!$is_valid){
                $_SESSION["error"] = "Felhasználó hozzáadás nem sikerült az adatbázishoz!";
                return false;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Felhasználó hozzáadás nem sikerült az adatbázishoz!";
            return false;}
    }
    public static function user_delete_from_database($user): bool
    {
        try{
            $database = new database_connection();
            database_user::delete_from_database($database->getConnection(), $user->getUserId());
            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Felhasználó törlése nem sikerült az adatbázisból!";
            return false;
        }
    }

    public static function user_modify_in_database($user): ?bool
    {
        try{
            $database = new database_connection();

            $is_valid = database_user::modify_in_database($database->getConnection(),
                $user->getUserId(), password_hash($user->getUserPassword_1(), PASSWORD_DEFAULT) ,$user->getUserPhone(),
                $user->getUserEmail(), $user->getUserLevel(), $user->getUserName());

            if(!$is_valid){
                $_SESSION["error"] = "Felhasználó módosítása nem sikerült az adatbázisban!";
                return false;
            }

            if (!$user->getUserSubjects()) return true;

            $subjects_array = service_common::prepare_array_items($user->getUserSubjects());
            $is_valid = database_subject::modify_in_database($database->getConnection(), $user->getUserId(), $subjects_array);

            if(!$is_valid){
                $_SESSION["error"] = "Felhasználó módosítása nem sikerült az adatbázisban!";
                return false;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Felhasználó módosítása nem sikerült az adatbázisban!";
            return false;
        }
    }

    public static function user_get_all_user_with_subjects(): ?array
    {
        try{
            $database = new database_connection();
            return database_user::get_all_user_with_subjects($database->getConnection());
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Hiba a felhasználók lekérdezése közben!";
            return null;
        }
    }

    public static function get_user_id_by_email($conn, $user_email): ?string {
        $user_id = null;
        if(!empty($user_email)){
            $user_array = database_user::get_user_id_by_email($conn, [$user_email]);
            if(!empty($user_array)){
                $user_id = $user_array[0];}
            else{
                $_SESSION["error"] = "Az órarend lekérdezése nem sikerült az adatbázisból! Hibás e-mail cím!";
            }
        }

        return $user_id;
    }

}

