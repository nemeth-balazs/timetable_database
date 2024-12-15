<?php

class database_class {
    public static function add_to_database($conn, $year, $letter, $headcount, $start_year, $headmaster, $division){
        if (!$conn) {
            error_log("Connection error - database_class - add_to_database");
            die("Connection error - database_class - add_to_database");
        }

        $stmt = mysqli_prepare( $conn,"INSERT INTO class(year, letter, headcount, start_year, headmaster, division) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "isiiss", $year, $letter, $headcount, $start_year, $headmaster, $division );

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            die("Error executing query.");
        }

        return true;
    }

    public static function delete_from_database($conn, $year, $letter){
        if (!$conn) {
            error_log("Connection error - database_class - delete_from_database");
            die("Connection error - database_class - delete_from_database");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM class WHERE `year` = ? and letter = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        mysqli_stmt_bind_param($stmt, "is", $year, $letter);

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Error executing query: " . mysqli_error($conn));
            mysqli_stmt_close($stmt);
            die("Error executing query.");
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public static function modify_in_database($conn, $year, $letter, $headcount, $start_year, $headmaster, $division){
        if (!$conn) {
            error_log("Connection error - database_class - modify_in_database");
            die("Connection error - database_class - modify_in_database");
        }

        $fields = [];
        $params = [];
        $types = "";

        if ($headcount !== null) {
            $fields[] = "headcount = ?";
            $params[] = $headcount;
            $types .= "i"; // string
        }
        if ($start_year !== null) {
            $fields[] = "start_year = ?";
            $params[] = $start_year;
            $types .= "i"; // integer
        }
        if ($division !== null) {
            $fields[] = "division = ?";
            $params[] = $division;
            $types .= "s"; // string
        }

        if ($headmaster !== null) {
            $fields[] = "headmaster = ?";
            $params[] = $headmaster;
            $types .= "s"; // string
        }

        if (empty($fields)) return true;

        $sql = "UPDATE class SET " . implode(", ", $fields) . " WHERE `year` = ? and letter = ?";
        $params[] = $year;
        $types .= "i";
        $params[] = $letter;
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

    public static function class_get_all($conn): ?array
    {
        if (!$conn) {
            error_log("Connection error - database_class - get_all");
            die("Connection error - database_class - get_all");
        }

        $stmt = mysqli_prepare($conn, "SELECT `year`, letter, headcount, start_year, headmaster, division FROM class");
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

        if (!mysqli_stmt_bind_result($stmt, $year, $letter, $headcount, $start_year, $headmaster, $division)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $class_array = [];
        while (mysqli_stmt_fetch($stmt)) {
            $class_array[] = new school_class($year, $letter, $headcount, $start_year, $headmaster, $division);
        }

        mysqli_stmt_close($stmt);

        return $class_array;
    }

    public static function fill_class_max_headcount_from_database($conn, $year, $letter){
        if (!$conn) {
            error_log("Connection error - database_teacher_course - fill_class_max_headcount_from_database");
            die("Connection error - database_teacher_course - fill_class_max_headcount_from_database");
        }

        if (!empty($year) && !empty($letter)) {
            $query = "SELECT year, letter, headcount FROM class WHERE year = ? AND letter = ? AND headcount = (SELECT MAX(headcount) FROM class WHERE year = ? AND letter = ?)";
        } else if (!empty($year) && empty($letter)) {
            $query = "SELECT year, letter, headcount FROM class WHERE year = ? AND headcount = (SELECT MAX(headcount) FROM class WHERE year = ?)";
        } else if (empty($year) && !empty($letter)) {
            $query = "SELECT year, letter, headcount FROM class WHERE letter = ? AND headcount = (SELECT MAX(headcount) FROM class WHERE letter = ?)";
        } else {
            $query = "SELECT year, letter, headcount FROM class WHERE headcount = (SELECT MAX(headcount) FROM class)";}

        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        if (!empty($year) && !empty($letter)) {
            mysqli_stmt_bind_param($stmt, "isis", $year, $letter, $year, $letter);
        }
        else if(!empty($year) && empty($letter)) {
            mysqli_stmt_bind_param($stmt, "ii", $year, $year);
        }
        else if(empty($year) && !empty($letter)) {
            mysqli_stmt_bind_param($stmt, "ss", $letter, $letter);
        }

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Failed to execute statement for fill_teacher_course");
            die("Failed to execute statement.");
        }

        if (!mysqli_stmt_bind_result($stmt, $year, $letter, $headcount)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $school_class_headcount_array = [];
        while (mysqli_stmt_fetch($stmt)) {
            $school_class_headcount_array[] = new school_class_headcount($year, $letter, $headcount);
        }

        mysqli_stmt_close($stmt);
        return $school_class_headcount_array;
    }

    public static function fill_class_sum_headcount_from_database($conn, $year, $letter){
        if (!$conn) {
            error_log("Connection error - database_teacher_course - fill_class_max_headcount_from_database");
            die("Connection error - database_teacher_course - fill_class_max_headcount_from_database");
        }

        $query = "SELECT year, letter, SUM(headcount) AS sum_by_year FROM class";
        $conditions = [];
        if (!empty($year)) {
            $conditions[] = "year = ?";
        }
        if (!empty($letter)) {
            $conditions[] = "letter = ?";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY year ORDER BY sum_by_year desc";

        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            error_log("Failed to prepare statement: " . mysqli_error($conn));
            die("Failed to prepare statement.");
        }

        if (!empty($year) && !empty($letter)) {
            mysqli_stmt_bind_param($stmt, "ss", $year, $letter);
        } elseif(!empty($year)) {
            mysqli_stmt_bind_param($stmt, "s", $year);
        } elseif(!empty($letter)) {
            mysqli_stmt_bind_param($stmt, "s", $letter);
        }

        $is_valid = mysqli_stmt_execute($stmt);
        if (!$is_valid) {
            error_log("Failed to execute statement for fill_teacher_course");
            die("Failed to execute statement.");
        }

        if (!mysqli_stmt_bind_result($stmt, $year, $letter, $sum_headcount)) {
            error_log("Failed to bind results.");
            die("Failed to bind results.");
        }

        $school_class_headcount_array = [];
        while (mysqli_stmt_fetch($stmt)) {
            $school_class_headcount_array[] = new school_class_headcount($year, $letter, $sum_headcount);
        }

        mysqli_stmt_close($stmt);
        return $school_class_headcount_array;
    }



}
