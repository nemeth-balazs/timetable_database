<?php

include_once __DIR__ . "/database_subject.php";

class database_user {
    public static function add_to_database($conn, $id, $password, $phone, $email, $user_level, $name){
        if (!$conn) {
            error_log("Connection error - database_user - add_to_database");
            die("Connection error - database_user - add_to_database");
        }

        $stmt = mysqli_prepare( $conn,"INSERT INTO user(id, password, phone, email, user_level, name) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }
        mysqli_stmt_bind_param($stmt, "ssssss", $id, $password, $phone, $email, $user_level, $name );
        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing insert query: " . mysqli_error($conn));
            die("Error executing insert query.");
        }

        return true;
    }

    public static function create_new_account_add_to_database($conn, $id, $password){
        if (!$conn) {
            error_log("Connection error - database_user - add_to_database");
            die("Connection error - database_user - add_to_database");
        }

        $stmt = mysqli_prepare( $conn,"INSERT INTO user(id, password) VALUES (?, ?)");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }
        mysqli_stmt_bind_param($stmt, "ss", $id, $password);
        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing insert query: " . mysqli_error($conn));
            die("Error executing insert query.");
        }

        return true;
    }

    public static function delete_from_database($conn, $user_id){
        if (!$conn) {
            error_log("Connection error - database_user - delete_from_database");
            die("Connection error - database_user - delete_from_database");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM user WHERE id = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "s", $user_id);

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            mysqli_stmt_close($stmt);
            die("Error executing query.");
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public static function modify_in_database($conn, $user_id, $password, $phone, $email, $user_level, $name){
        if (!$conn) {
            error_log("Connection error - database_user - modify_in_database");
            die("Connection error - database_user - modify_in_database");
        }

        $fields = [];
        $params = [];
        $types = "";

        if ($password !== null) {
            $fields[] = "password = ?";
            $params[] = $password;
            $types .= "s";
        }
        if ($phone !== null) {
            $fields[] = "phone = ?";
            $params[] = $phone;
            $types .= "s";
        }
        if ($email !== null) {
            $fields[] = "email = ?";
            $params[] = $email;
            $types .= "s";
        }
        if ($user_level !== null) {
            $fields[] = "user_level = ?";
            $params[] = $user_level;
            $types .= "s";
        }
        if ($name !== null) {
            $fields[] = "name = ?";
            $params[] = $name;
            $types .= "s";
        }

        if (empty($fields)) return true;

        $sql = "UPDATE user SET " . implode(", ", $fields) . " WHERE id = ?";
        $params[] = $user_id;
        $types .= "s";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, $types, ...$params);

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_stmt_error($stmt));
            die("Error executing query.");
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public static function get_user_id_by_email($conn, $email_array) {
        if (!$conn) {
            error_log("Connection error - database_user - get_user_id_by_email");
            die("Connection error - database_user - get_user_id_by_email");
        }

        $stmt = mysqli_prepare($conn, "SELECT id FROM user WHERE email = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        $user_array = [];
        foreach ($email_array as $email) {
            if (empty($email)) continue;

            mysqli_stmt_bind_param($stmt, "s", $email );
            $is_valid = mysqli_stmt_execute($stmt);
            if (!$is_valid) {
                error_log("Failed to select user for user email: $email");
                mysqli_stmt_close($stmt);
                continue;
            }

            mysqli_stmt_bind_result($stmt, $id);
            if (mysqli_stmt_fetch($stmt))
                $user_array[] =  $id;
        }

        mysqli_stmt_close($stmt);

        return $user_array;
    }

    public static function get_email_by_user_id($conn, $user_id_array) {
        if (!$conn) {
            error_log("Connection error - database_user - database_user");
            die("Connection error - database_user - database_user");
        }

        $stmt = mysqli_prepare($conn, "SELECT email FROM user WHERE id = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        $email_array = [];
        foreach ($user_id_array as $id) {
            if (empty($id)) continue;

            mysqli_stmt_bind_param($stmt, "s", $id );
            $is_valid = mysqli_stmt_execute($stmt);
            if (!$is_valid) {
                error_log("Failed to select user for user email: $id");
                mysqli_stmt_close($stmt);
                continue;
            }

            mysqli_stmt_bind_result($stmt, $email);
            if (mysqli_stmt_fetch($stmt))
                $email_array[] =  $email;
        }

        mysqli_stmt_close($stmt);

        return $email_array;
    }

    public static function get_all_user_with_subjects($conn): ?array
    {
        $user_array = database_user::get_all_user_without_subjects($conn);
        database_subject::fill_all_subjects_for_users($conn, $user_array);

        return $user_array;
    }

    static function get_all_user_without_subjects($conn){
        if (!$conn) {
            error_log("Connection error - database_user - get_all");
            die("Connection error - database_user - get_all");
        }

        $stmt = mysqli_prepare($conn, "SELECT id, name, email, phone, password, user_level FROM user");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Failed to select users.");
            mysqli_stmt_close($stmt);
            die("Failed to select users.");
        }

        if (!mysqli_stmt_bind_result($stmt, $id, $name, $email, $phone, $password, $user_level)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $user_array = [];
        while (mysqli_stmt_fetch($stmt)) {
            $user_array[] = new user($id, $name, $email, $phone, $password, $password, $user_level, []);
        }

        mysqli_stmt_close($stmt);

        return $user_array;
    }
}