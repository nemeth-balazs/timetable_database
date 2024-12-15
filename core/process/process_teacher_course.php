<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__ . "/../services/service_teacher_course.php";

$process_teacher_course = new process_teacher_course();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['get_teacher_course_by_user_name'])) {
        $process_teacher_course->process_get_teacher_course();
    }
}

header("Location: ../../ui/pages/page_teacher_course.php");

class process_teacher_course {
    public function process_get_teacher_course(): bool
    {
        $user_email = $this->get_teacher();

        $is_valid = service_teacher_course::fill_teacher_course_from_database($user_email);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Lekérdezés sikeres volt!";
        return true;
    }

    private function get_teacher(): ?string
    {
        $user_email = isset($_POST['teacher_course_teacher_email']) && !empty($_POST['teacher_course_teacher_email']) ? trim($_POST['teacher_course_teacher_email']) : null;
        return $user_email;
    }
}






