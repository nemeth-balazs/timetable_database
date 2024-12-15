<?php

include_once __DIR__."/../classes/school_Class_course.php";
class database_class_course
{
    public static function add_to_database($conn, $room_number, $day, $start, $year_array, $letter_array)
    {
        if (!$conn) {
            error_log("Connection error - database_teacher_course - add_to_database");
            die("Connection error - database_teacher_course - add_to_database");
        }

        $insert_stmt = mysqli_prepare($conn, "INSERT INTO class_course(room_number, day, start, year, letter) VALUES (?, ?, ?, ?, ?)");
        if (!$insert_stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        $zipped = array_map(null, $year_array, $letter_array);
        foreach ($zipped as [$year, $letter]) {
            if (empty($year) || empty($letter)) continue;

            mysqli_stmt_bind_param($insert_stmt, "sssis", $room_number, $day, $start, $year, $letter );
            $is_valid = mysqli_stmt_execute($insert_stmt);
            if (!$is_valid) {
                error_log("Failed to insert class_course: $room_number, $day, $start, $year, $letter");
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

        $stmt = mysqli_prepare($conn, "DELETE FROM class_course WHERE room_number = ? and `day` = ? and start = ?");
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

    public static function modify_in_database($conn, $room_number, $day, $start, $year_array, $letter_array)
    {
        if(empty($year_array) || empty($letter_array)) return true;

        database_class_course::delete_from_database($conn, $room_number, $day, $start);
        database_class_course::add_to_database($conn, $room_number, $day, $start, $year_array, $letter_array);
        return true;
    }

    public static function course_fill_class_for_course($conn, $courses){

        if (!$conn) {
            error_log("Connection error - database_teacher_course - course_fill_class_for_course");
            die("Connection error - database_teacher_course - course_fill_class_for_course");
        }

        $stmt = mysqli_prepare($conn, "SELECT `year`, letter FROM class_course WHERE room_number = ? and `day` = ? and start = ?");
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

            if (!mysqli_stmt_bind_result($stmt, $year, $letter)) {
                error_log("Failed to bind results.");
                die("Failed to bind results.");
            }

            while (mysqli_stmt_fetch($stmt)) {
                $course->addClass($year.$letter);
            }
        }
    }

    public static function fill_class_course_by_year_and_letter($conn, $year, $letter){
        if (!$conn) {
            error_log("Connection error - database_teacher_course - fill_class_course_by_year_and_letter");
            die("Connection error - database_teacher_course - fill_class_course_by_year_and_letter");
        }

        $query = "select cc.year, cc.letter, cc.room_number, cc.day, cc.start, c.subject_name from class_course as cc join course as c on cc.room_number = c.room_number and cc.day = c.day and cc.start = c.start";
        if (!empty($year) && !empty($letter)) {
            $query .= " where cc.year = ? and cc.letter = ? order by cc.day, cc.start";
        }
        else if(!empty($year) && empty($letter)) {
            $query .= " where cc.year = ? order by cc.year, cc.letter, cc.day, cc.start";
        }
        else if(empty($year) && !empty($letter)) {
            $query .= " where cc.letter = ? order by cc.year, cc.letter, cc.day, cc.start";
        }
        else {
            $query .= " order by cc.year, cc.letter, cc.day, cc.start";
        }

        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        if (!empty($year) && !empty($letter)) {
            mysqli_stmt_bind_param($stmt, "ss", $year, $letter);
        }
        else if(!empty($year) && empty($letter)) {
            mysqli_stmt_bind_param($stmt, "s", $year);
        }
        else if(empty($year) && !empty($letter)) {
            mysqli_stmt_bind_param($stmt, "s", $letter);
        }

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Failed to execute statement for fill_teacher_course");
            die("Failed to execute statement.");
        }

        if (!mysqli_stmt_bind_result($stmt, $year, $letter, $room_number, $day, $start, $subject_name)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $class_course = [];
        while (mysqli_stmt_fetch($stmt)) {
            $class_course[] = new school_Class_course($year, $letter, $room_number, $day, $start, $subject_name);
        }

        mysqli_stmt_close($stmt);
        return $class_course;
    }
}