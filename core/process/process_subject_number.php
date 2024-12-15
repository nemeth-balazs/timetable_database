<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__ . "/../services/service_subject_number.php";

$process_subject_number = new process_subject_number();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['get_subject_number_by_teacher'])) {
        $process_subject_number->process_subjects();
    }
}

header("Location: ../../ui/pages/page_subject_number.php");

class process_subject_number {
    public function process_subjects(): bool
    {
        $user_email = $this->get_teacher();

        $is_valid = service_subject_number::fill_subject_number_from_database($user_email);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Lekérdezés sikeres volt!";
        return true;
    }

    private function get_teacher(): ?string
    {
        $user_email = isset($_POST['subject_number_teacher_email']) && !empty($_POST['subject_number_teacher_email']) ? trim($_POST['subject_number_teacher_email']) : null;
        return $user_email;
    }
}






