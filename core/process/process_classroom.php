<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__."/../classes/classroom.php";
include_once __DIR__ . "/../services/service_classroom.php";

$process_classroom = new process_classroom();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['classroom_add'])) {
        $process_classroom ->process_add_classroom();
    } elseif (isset($_POST['classroom_delete'])) {
        $process_classroom ->process_delete_classroom();
    } elseif (isset($_POST['classroom_modify'])) {
        $process_classroom ->process_modify_classroom();
    }
}

header("Location: ../../ui/pages/page_classroom.php");

class process_classroom{
    public function process_add_classroom(): bool
    {
        $new_classroom = $this->get_classroom_add();
        if (!$new_classroom) return false;

        $is_valid = service_classroom::classroom_validate($new_classroom);
        if (!$is_valid) return false;

        $is_valid = service_classroom::classroom_add_to_database($new_classroom);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Osztályterem hozzáadása sikeres volt!";
        return true;
    }

    public function process_modify_classroom(): bool
    {
        $new_classroom = $this->get_classroom_modify();
        if (!$new_classroom) return false;

        $is_valid = service_classroom::classroom_validate($new_classroom);
        if (!$is_valid) return false;

        $is_valid = service_classroom::classroom_modify_in_database($new_classroom);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Osztályterem módosítása sikeres volt!";
        return true;
    }

    public function process_delete_classroom(): bool
    {
        $new_classroom = $this->get_classroom_modify();
        if (!$new_classroom) return false;

        $is_valid = service_classroom::classroom_delete_from_database($new_classroom);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Osztályterem törlése sikeres volt!";
        return true;
    }

    private function get_classroom_add(): ?classroom
    {
        $new_classroom = $this->get_classroom();
        if ( !$new_classroom->getClassroomNumber() || !$new_classroom->getClassroomCapacity()){
            $_SESSION["error"] = "Nem megfelelő osztályterem paraméterek!";
            return null;}

        return $new_classroom;
    }

    private function get_classroom_modify(): ?classroom
    {
        $new_classroom = $this->get_classroom();
        if (!$new_classroom->getClassroomNumber()) {
            $_SESSION["error"] = "Nem megfelelő osztályterem paraméterek!";
            return null;
        }

        return $new_classroom;
    }

    private function get_classroom(): classroom
    {
        $classroom_number            = isset($_POST['classroom_number']) && !empty($_POST['classroom_number']) ? $_POST['classroom_number'] : null;
        $classroom_capacity          = isset($_POST['classroom_capacity']) && !empty($_POST['classroom_capacity']) ? $_POST['classroom_capacity'] : null;
        $classroom_equipment         = isset($_POST['classroom_equipment']) && !empty($_POST['classroom_equipment']) ? $_POST['classroom_equipment'] : null;

        return new classroom($classroom_number, $classroom_capacity, $classroom_equipment);
    }


}
