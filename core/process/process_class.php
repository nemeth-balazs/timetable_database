<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__."/../classes/school_class.php";
include_once __DIR__ . "/../services/service_class.php";

$process_class = new process_class();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['class_add'])) {
        $process_class ->process_add_class();
    } elseif (isset($_POST['class_delete'])) {
        $process_class ->process_delete_class();
    } elseif (isset($_POST['class_modify'])) {
        $process_class ->process_modify_class();
    }
}

header("Location: ../../ui/pages/page_class.php");

class process_class{
    public function process_add_class(): bool
    {
        $new_class = $this->get_class_add();
        if (!$new_class) return false;

        $is_valid = service_class::class_validate($new_class);
        if (!$is_valid) return false;

        $is_valid = service_class::class_add_to_database($new_class);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Osztály hozzáadása sikeres volt!";
        return true;
    }

    public function process_modify_class(): bool
    {
        $new_class = $this->get_class_modify();
        if (!$new_class) return false;

        $is_valid = service_class::class_validate($new_class);
        if (!$is_valid) return false;

        $is_valid = service_class::class_modify_in_database($new_class);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Osztály módosítása sikeres volt!";
        return true;
    }

    public function process_delete_class(): bool
    {
        $new_class = $this->get_class_modify();
        if (!$new_class) return false;

        $is_valid = service_class::class_delete_from_database($new_class);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Osztály törlése sikeres volt!";
        return true;
    }

    private function get_class_add(): ?school_class
    {
        $new_class = $this->get_class();
        if ( !$new_class->getYear() ||!$new_class->getLetter() ||
            !$new_class->getHeadcount() || !$new_class->getStartYear() ||
            !$new_class->getHeadmasterEmail() || !$new_class->getDivision()){
            $_SESSION["error"] = "Nem megfelelő osztály paraméterek!";
            return null;}

        return $new_class;
    }

    private function get_class_modify(): ?school_class
    {
        $new_class = $this->get_class();
        if (!$new_class->getYear() || !$new_class->getLetter()){
            $_SESSION["error"] = "Nem megfelelő osztály paraméterek!";
            return null;
        }

        return $new_class;
    }
    private function get_class(): school_class
    {
        $class_year             = isset($_POST['class_year']) && !empty($_POST['class_year']) ? $_POST['class_year'] : null;
        $class_letter           = isset($_POST['class_letter']) && !empty($_POST['class_letter']) ? $_POST['class_letter'] : null;
        $class_headcount        = isset($_POST['class_headcount']) && !empty($_POST['class_headcount']) ? $_POST['class_headcount'] : null;
        $class_start_year       = isset($_POST['class_start_year']) && !empty($_POST['class_start_year']) ? $_POST['class_start_year'] : null;
        $class_headmaster_email = isset($_POST['class_headmaster_email']) && !empty($_POST['class_headmaster_email']) ? $_POST['class_headmaster_email'] : null;
        $class_division         = isset($_POST['class_division']) && !empty($_POST['class_division']) ? $_POST['class_division'] : null;

        return new school_class($class_year, $class_letter, $class_headcount, $class_start_year, $class_headmaster_email, $class_division);
    }
}
