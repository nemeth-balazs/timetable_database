<?php
include_once __DIR__."/../classes/subject_number.php";
class database_subject
{
    public static function add_to_database($conn, $user_id, $subjects_array){
        if (!$conn) {
            error_log("Connection error - database_subject - add_to_database");
            die("Connection error - database_subject - add_to_database");
        }

        $insert_stmt = mysqli_prepare($conn, "INSERT INTO subject(user_id, name) VALUES (?, ?)");
        if (!$insert_stmt) {
            error_log("Failed to prepare insert statement: " . mysqli_error($conn));
            return false;
        }

        foreach ($subjects_array as $subject) {
            mysqli_stmt_bind_param($insert_stmt, "ss", $user_id, $subject);
            $is_valid = mysqli_stmt_execute($insert_stmt);
            if (!$is_valid) {
                error_log("Failed to insert subject: $subject for user_id: $user_id");
                mysqli_stmt_close($insert_stmt);
                return false;
            }
        }

        mysqli_stmt_close($insert_stmt);
        return true;
    }

    public static function delete_from_database($conn, $user_id){
        if (!$conn) {
            error_log("Connection error - database_subject - delete_from_database");
            die("Connection error - database_subject - delete_from_database");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM subject WHERE user_id = ?");
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

    public static function modify_in_database($conn, $user_id, $subjects_array)
    {
        if(empty($subjects_array)) return true;

        database_subject::delete_from_database($conn, $user_id);
        database_subject::add_to_database($conn, $user_id, $subjects_array);
        return true;
    }

    public static function fill_all_subjects_for_users($conn, $user_array){
        if (!$conn) {
            error_log("Connection error - database_subject - get_all");
            die("Connection error - database_subject - get_all");
        }

        $stmt = mysqli_prepare($conn, "SELECT name FROM subject WHERE user_id = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        foreach ($user_array as $user) {
            $userId = $user->getUserId();
            mysqli_stmt_bind_param($stmt, "s", $userId);
            $is_valid = mysqli_stmt_execute($stmt);
            if (!$is_valid) {
                error_log("Failed to execute statement for user_id: $user->getUserId()");
                continue;
            }

            if (!mysqli_stmt_bind_result($stmt, $name)) {
                error_log("Failed to bind results.");
                die("Failed to bind results.");
            }

            $subjects_array = [];
            while (mysqli_stmt_fetch($stmt)) {
                $subjects_array[] = $name;
            }

            $user->setUserSubjects($subjects_array);
        }

        mysqli_stmt_close($stmt);
    }

    public static function fill_subject_number_from_database($conn, $user_id){
        if (!$conn) {
            error_log("Connection error - database_teacher_course - fill_teacher_course_from_database");
            die("Connection error - database_teacher_course - fill_teacher_course_from_database");
        }

        $query = "SELECT u.name, u.email, u.phone, COUNT(*) AS subject_number FROM user AS u JOIN subject AS s ON u.id = s.user_id";
        if ($user_id) {
            $query .= " WHERE u.id = ?";
        }
        $query .= " GROUP BY u.id ORDER BY subject_number desc";


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

        if (!mysqli_stmt_bind_result($stmt, $user_name, $user_email, $user_phone, $subject_number)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $subject_number_array = [];
        while (mysqli_stmt_fetch($stmt)) {
            $subject_number_array[] = new subject_number($user_name, $user_email, $user_phone, $subject_number);
        }

        mysqli_stmt_close($stmt);
        return $subject_number_array;
    }
}