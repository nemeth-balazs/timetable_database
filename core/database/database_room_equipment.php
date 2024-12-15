<?php

class database_room_equipment
{
    public static function add_to_database($conn, $room_number, $equipment_array){

        if (!$conn) {
            error_log("Connection error - database_room_equipment - add_to_database");
            die("Connection error - database_room_equipment - add_to_database");
        }

        $insert_stmt = mysqli_prepare($conn, "INSERT INTO room_equipment (name, room_number) VALUES (?, ?)");
        if (!$insert_stmt) {
            error_log("Failed to prepare insert statement: " . mysqli_error($conn));
            return false;
        }

        foreach ($equipment_array as $equipment) {
            mysqli_stmt_bind_param($insert_stmt, "ss", $equipment, $room_number);
            $is_valid = mysqli_stmt_execute($insert_stmt);
            if (!$is_valid) {
                error_log("Failed to insert equipment: $equipment for room number: $room_number");
                mysqli_stmt_close($insert_stmt);
                return false;
            }
        }

        mysqli_stmt_close($insert_stmt);
        return true;
    }

    public static function delete_from_database($conn, $room_number){
        if (!$conn) {
            error_log("Connection error - database_room_equipment - delete_from_database");
            die("Connection error - database_room_equipment - delete_from_database");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM room_equipment WHERE room_number = ?");
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

    public static function modify_in_database($conn, $room_number, $equipment_array)
    {
        if(empty($equipment_array)) return true;

        database_room_equipment::delete_from_database($conn, $room_number);
        database_room_equipment::add_to_database($conn, $room_number, $equipment_array);
        return true;
    }

    public static function fill_all_equipment_for_classroom($conn, $classroom_array){
        if (!$conn) {
            error_log("Connection error - database_room_equipment - get_all");
            die("Connection error - database_room_equipment - get_all");
        }

        $stmt = mysqli_prepare($conn, "SELECT name FROM room_equipment WHERE room_number = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        foreach ($classroom_array as $classroom) {
            $room_number = $classroom->getClassroomNumber();
            mysqli_stmt_bind_param($stmt, "s", $room_number);
            $is_valid = mysqli_stmt_execute($stmt);
            if (!$is_valid) {
                error_log("Failed to execute statement for classroom: $room_number");
                continue;
            }

            if (!mysqli_stmt_bind_result($stmt, $name)) {
                error_log("Failed to bind results.");
                die("Failed to bind results.");
            }

            $equipment_array = [];
            while (mysqli_stmt_fetch($stmt)) {
                $equipment_array[] = $name;
            }

            $classroom->setClassroomEquipment($equipment_array);
        }

        mysqli_stmt_close($stmt);
    }
}