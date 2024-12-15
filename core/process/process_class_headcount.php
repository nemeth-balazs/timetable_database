<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__ . "/../services/service_class_headcount.php";
include_once __DIR__ . "/../services/service_class.php";
include_once __DIR__."/../classes/school_class.php";


$process_class_headcount = new process_class_headcount();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['get_class_max_headcount'])) {
        $process_class_headcount->process_get_class_max_headcount();
    }
    else if (isset($_POST['get_class_sum_headcount'])) {
        $process_class_headcount->process_get_class_sum_headcount();
    }
}

header("Location: ../../ui/pages/page_class_headcount.php");

class process_class_headcount {
    public function process_get_class_max_headcount(): bool
    {
        $new_school_class = $this->get_max_class();

        $is_valid = service_class::class_validate($new_school_class);
        if (!$is_valid) return false;

        $is_valid = service_class_headcount::fill_class_max_headcount_from_database($new_school_class->getYear(), $new_school_class->getLetter());
        if (!$is_valid) return false;

        $_SESSION["message"] = "Lekérdezés sikeres volt!";
        return true;
    }

    public function process_get_class_sum_headcount(): bool
    {
        $new_school_class = $this->get_sum_class();

        $is_valid = service_class::class_validate($new_school_class);
        if (!$is_valid) return false;

        $is_valid = service_class_headcount::fill_class_sum_headcount_from_database($new_school_class->getYear(), $new_school_class->getLetter());
        if (!$is_valid) return false;

        $_SESSION["message"] = "Lekérdezés sikeres volt!";
        return true;
    }

    private function get_max_class(): school_class
    {
        $year = isset($_POST['class_headcount_max_year']) && !empty($_POST['class_headcount_max_year']) ? trim($_POST['class_headcount_max_year']) : null;
        $letter = isset($_POST['class_headcount_max_letter']) && !empty($_POST['class_headcount_max_letter']) ? trim($_POST['class_headcount_max_letter']) : null;

        return new school_class($year, $letter, "", "", "", "");
    }

    private function get_sum_class(): school_class
    {
        $year = isset($_POST['class_headcount_sum_year']) && !empty($_POST['class_headcount_sum_year']) ? trim($_POST['class_headcount_sum_year']) : null;
        $letter = isset($_POST['class_headcount_sum_letter']) && !empty($_POST['class_headcount_sum_letter']) ? trim($_POST['class_headcount_sum_letter']) : null;

        return new school_class($year, $letter, "", "", "", "");
    }
}






