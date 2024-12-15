<?php

class database_classroom
{
    public static function add_to_database($conn, $room_number, $room_capacity){
        if (!$conn) {
            error_log("Connection error - database_classroom - add_to_database");
            die("Connection error - database_classroom - add_to_database");
        }

        $stmt = mysqli_prepare( $conn,"INSERT INTO classroom(room_number, room_capacity) VALUES (?, ?)");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "si", $room_number, $room_capacity );
        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            die("Error executing query.");
        }

        return true;
    }

    public static function delete_from_database($conn, $room_number){
        if (!$conn) {
            error_log("Connection error - database_classroom - delete_from_database");
            die("Connection error - database_classroom - delete_from_database");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM classroom WHERE room_number = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "s", $room_number);

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            mysqli_stmt_close($stmt);
            die("Error executing query.");
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public static function modify_in_database($conn, $room_number, $room_capacity){
        if (!$conn) {
            error_log("Connection error - database_classroom - modify_in_database");
            die("Connection error - database_classroom - modify_in_database");
        }

        $fields = [];
        $params = [];
        $types = "";

        if ($room_capacity !== null) {
            $fields[] = "room_capacity = ?";
            $params[] = $room_capacity;
            $types .= "i";
        }

        if (empty($fields)) return true;

        $sql = "UPDATE classroom SET " . implode(", ", $fields) . " WHERE room_number = ?";
        $params[] = $room_number;
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
    public static function get_all_classroom_with_equipments($conn): ?array
    {
        $classroom_array = database_classroom::get_all_classroom_without_equipments($conn);
        database_room_equipment::fill_all_equipment_for_classroom($conn, $classroom_array);

        return $classroom_array;
    }

    static function get_all_classroom_without_equipments($conn){
        if (!$conn) {
            error_log("Connection error - database_classroom - get_all");
            die("Connection error - database_classroom - get_all");
        }

        $stmt = mysqli_prepare($conn, "SELECT room_number, room_capacity FROM classroom");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Failed to select classroom.");
            mysqli_stmt_close($stmt);
            die("Failed to select classroom.");
        }

        if (!mysqli_stmt_bind_result($stmt, $room_number, $room_capacity)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $classroom_array = [];
        while (mysqli_stmt_fetch($stmt)) {
            $classroom_array[] = new classroom($room_number, $room_capacity, []);
        }

        mysqli_stmt_close($stmt);

        return $classroom_array;
    }


}
