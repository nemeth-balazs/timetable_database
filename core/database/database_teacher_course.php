<?php

include_once __DIR__."/../classes/teacher_course.php";
class database_teacher_course
{
    public static function add_to_database($conn, $room_number, $day, $start, $user_array)
    {
        if (!$conn) {
            error_log("Connection error - database_teacher_course - add_to_database");
            die("Connection error - database_teacher_course - add_to_database");
        }

        $insert_stmt = mysqli_prepare($conn, "INSERT INTO teacher_course(room_number, day, start, user_id) VALUES (?, ?, ?, ?)");
        if (!$insert_stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        foreach ($user_array as $user_id) {
            if (empty($user_id)) continue;

            mysqli_stmt_bind_param($insert_stmt, "ssss", $room_number, $day, $start, $user_id );
            $is_valid = mysqli_stmt_execute($insert_stmt);
            if (!$is_valid) {
                error_log("Failed to insert teacher_course: $room_number, $day, $start for user_id: $user_id");
                mysqli_stmt_close($insert_stmt);
                return false;
            }
        }

        return true;
    }

    public static function delete_from_database($conn, $room_number, $day, $start){
        if (!$conn) {
            error_log("Connection error - database_teacher_course - delete_from_database");
            die("Connection error - database_teacher_course - delete_from_database");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM teacher_course WHERE room_number = ? and `day` = ? and start = ?");
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

    public static function modify_in_database($conn, $room_number, $day, $start, $user_array)
    {
        if(empty($user_array)) return true;

        database_teacher_course::delete_from_database($conn, $room_number, $day, $start);
        database_teacher_course::add_to_database($conn, $room_number, $day, $start, $user_array);
        return true;
    }

    public static function course_fill_user_id_for_course($conn, $courses){
        if (!$conn) {
            error_log("Connection error - database_teacher_course - course_fill_user_id_for_course");
            die("Connection error - database_teacher_course - course_fill_user_id_for_course");
        }

        $stmt = mysqli_prepare($conn, "SELECT user_id FROM teacher_course WHERE room_number = ? and `day` = ? and start = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        foreach ($courses as $course) {
            $room_number = $course->getRoomNumber();
            $day = $course->getDay();
            $start = $course->getStart();
            mysqli_stmt_bind_param($stmt, "sss", $room_number, $day, $start);
            $is_valid = mysqli_stmt_execute($stmt);
            if (!$is_valid) {
                error_log("Failed to execute statement.");
                continue;
            }

            if (!mysqli_stmt_bind_result($stmt, $user_id)) {
                error_log("Failed to bind results.");
                die("Failed to bind results.");
            }

            while (mysqli_stmt_fetch($stmt)) {
                $course->addTeacherEmail($user_id);
            }
        }
    }

    public static function fill_teacher_course_from_database($conn, $user_id){
        if (!$conn) {
            error_log("Connection error - database_teacher_course - fill_teacher_course_from_database");
            die("Connection error - database_teacher_course - fill_teacher_course_from_database");
        }

        $query = "SELECT c.room_number, c.day, c.start, c.subject_name, u.name FROM course AS c JOIN teacher_course AS tc ON c.room_number = tc.room_number AND c.day = tc.day AND c.start = tc.start JOIN user AS u ON tc.user_id = u.id";
        if ($user_id) {
            $query .= " WHERE tc.user_id = ? ORDER BY tc.day, tc.start";
        } else {
            $query .= " ORDER BY tc.user_id, tc.day, tc.start";
        }

        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        if ($user_id)
            mysqli_stmt_bind_param($stmt, "s", $user_id);

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Failed to execute statement for fill_teacher_course");
            die("Failed to execute statement.");
        }

        if (!mysqli_stmt_bind_result($stmt, $room_number, $day, $start, $subject_name, $user_id)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $teacher_course = [];
        while (mysqli_stmt_fetch($stmt)) {
            $teacher_course[] = new teacher_course($user_id, $room_number, $day, $start, $subject_name);
        }

        mysqli_stmt_close($stmt);
        return $teacher_course;
    }
}