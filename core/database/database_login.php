<?php

class database_login
{
    public static function get_password_and_user_level_by_user_id($conn, $user_id){
        if (!$conn) {
            error_log("Connection error - database_login - check_user_id_and_password");
            return null;
        }

        $stmt = mysqli_prepare($conn, "SELECT password, user_level FROM user WHERE id = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            return null;
        }

        mysqli_stmt_bind_param($stmt, "s", $user_id);
        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            return null;
        }

        mysqli_stmt_bind_result($stmt, $password, $user_level);
        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            return [$password, $user_level];
        }

        mysqli_stmt_close($stmt);
        return null;
    }
}