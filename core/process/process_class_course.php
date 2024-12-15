<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__ . "/../services/service_class_course.php";
include_once __DIR__ . "/../services/service_class.php";
include_once __DIR__."/../classes/school_class.php";


$process_class_course = new process_class_course();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['get_class_course_by_year_and_letter'])) {
        $process_class_course->process_get_class_course();
    }
}

header("Location: ../../ui/pages/page_class_course.php");

class process_class_course {
    public function process_get_class_course(): bool
    {
        $new_school_class = $this->get_class();

        $is_valid = service_class::class_validate_grade($new_school_class);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibás osztály év!";
            return false;
        }

        $is_valid = service_class::class_validate_letter($new_school_class);
        if(!$is_valid) {
            $_SESSION["error"] = "Hibás osztály betűjel!";
            return false;
        }

        $is_valid = service_class_course::fill_class_course_by_year_and_letter($new_school_class->getYear(), $new_school_class->getLetter());
        if (!$is_valid) return false;

        $_SESSION["message"] = "Lekérdezés sikeres volt!";
        return true;
    }
    private function get_class(): school_class
    {
        $year = isset($_POST['class_course_year']) && !empty($_POST['class_course_year']) ? trim($_POST['class_course_year']) : null;
        $letter = isset($_POST['class_course_letter']) && !empty($_POST['class_course_letter']) ? trim($_POST['class_course_letter']) : null;

        return new school_class( $year, $letter, 0, 0, "", "");
    }
}






