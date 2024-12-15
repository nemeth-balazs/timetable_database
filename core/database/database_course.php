<?php
class database_course
{
    public static function add_to_database($conn, $room_number, $day, $start, $subject_name)
    {
        if (!$conn) {
            error_log("Connection error - database_course - add_to_database");
            die("Connection error - database_course - add_to_database");
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO course(room_number, day, start, subject_name) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "ssss", $room_number, $day, $start, $subject_name );

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            die("Error executing query.");
        }

        return true;
    }

    public static function delete_from_database($conn, $room_number, $day, $start){
        if (!$conn) {
            error_log("Connection error - database_course - delete_from_database");
            die("Connection error - database_course - delete_from_database");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM course WHERE room_number = ? and `day` = ? and start = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "sss", $room_number, $day, $start);

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            mysqli_stmt_close($stmt);
            die("Error executing query.");
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public static function modify_in_database($conn, $room_number, $day, $start, $subject_name){
        if (empty($subject_name)) return true;

        if (!$conn) {
            error_log("Connection error - database_course - modify_in_database");
            die("Connection error - database_course - modify_in_database");
        }

        $stmt = mysqli_prepare($conn, "UPDATE course SET subject_name = ? WHERE room_number = ? and `day` = ? and start = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "ssss", $subject_name,$room_number, $day, $start);

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_stmt_error($stmt));
            die("Error executing query.");
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public static function course_get_all($conn): ?array
    {
        if (!$conn) {
            error_log("Connection error - database_course - course_get_all");
            die("Connection error - database_course - course_get_all");
        }

        $stmt = mysqli_prepare($conn, "SELECT room_number,`day`, start, subject_name FROM course");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Failed to select class.");
            mysqli_stmt_close($stmt);
            die("Failed to select class.");
        }

        if (!mysqli_stmt_bind_result($stmt, $room_number, $day, $start, $subject_name)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $course_array = [];
        while (mysqli_stmt_fetch($stmt)) {
            $course_array[] = new course($room_number, $day, $start, $subject_name, [], []);
        }

        mysqli_stmt_close($stmt);

        return $course_array;
    }

}