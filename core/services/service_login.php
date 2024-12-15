<?php

include_once __DIR__."/../database/database_login.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__."/../classes/user.php";

class service_login
{
    public static function check_user_id_and_password($user): bool
    {
        try{
            $database = new database_connection();

            [$password_in_database, $user_level]  = database_login::get_password_and_user_level_by_user_id($database->getConnection(), $user->getUserId());
            if (empty($password_in_database)){
                $_SESSION["error"] = "Nem megfelelő felhasználó!";
                return false;
            }

            $is_password_valid = password_verify($user->getUserPassword_1(), $password_in_database);
            if (empty($is_password_valid)) {
                $_SESSION["error"] = "Nem megfelelő jelszó!";
                return false;
            }

            $user->setUserLevel($user_level);
            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Nem megfelelő felhasználó név!";
            return false;
        }
    }
}