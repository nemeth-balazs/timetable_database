<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__."/../classes/course.php";
include_once __DIR__ . "/../services/service_course.php";

$process_course = new process_course();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['course_add'])) {
        $process_course ->process_add_course();
    } elseif (isset($_POST['course_delete'])) {
        $process_course ->process_delete_course();
    } elseif (isset($_POST['course_modify'])) {
        $process_course ->process_modify_course();
    }
}

header("Location: ../../ui/pages/page_course.php");

class process_course{
    public function process_add_course(): bool
    {
        $new_course = $this->get_course_add();
        if (!$new_course) return false;

        $is_valid = service_course::course_validate($new_course);
        if (!$is_valid) return false;

        $is_valid = service_course::course_add_to_database($new_course);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Tanóra hozzáadása sikeres volt!";
        return true;
    }

    public function process_modify_course(): bool
    {
        $new_course = $this->get_course_modify();
        if (!$new_course) return false;

        $is_valid = service_course::course_validate($new_course);
        if (!$is_valid) return false;

        $is_valid = service_course::course_modify_in_database($new_course);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Tanóra módosítása sikeres volt!";
        return true;
    }

    public function process_delete_course(): bool
    {
        $new_course = $this->get_course_modify();
        if (!$new_course) return false;

        $is_valid = service_course::course_delete_from_database($new_course);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Tanóra törlése sikeres volt!";
        return true;
    }

    private function get_course_add(): ?course
    {
        $new_course = $this->get_course();
        if ( !$new_course->getRoomNumber() || !$new_course->getDay() ||
            !$new_course->getStart() || !$new_course->getSubjectName() ||
            !$new_course->getTeacherEmail_array() || !$new_course->getClass_array()){
            $_SESSION["error"] = "Nem megfelelő tanóra paraméterek!";
            return null;
        }

        return $new_course;
    }

    private function get_course_modify(): ?course
    {
        $new_course = $this->get_course();
        if (!$new_course->getRoomNumber() || !$new_course->getDay() || !$new_course->getStart()){
            $_SESSION["error"] = "Nem megfelelő tanóra paraméterek!";
            return null;
        }

        return $new_course;
    }

    private function get_course(): course
    {
        $course_room_number         = isset($_POST['course_room_number']) && !empty($_POST['course_room_number']) ? trim($_POST['course_room_number']) : null;
        $course_day                 = isset($_POST['course_day']) && !empty($_POST['course_day']) ? trim($_POST['course_day']) : null;
        $course_start               = isset($_POST['course_start']) && !empty($_POST['course_start']) ? trim($_POST['course_start']) : null;
        $course_subject_name        = isset($_POST['course_subject_name']) && !empty($_POST['course_subject_name']) ? trim($_POST['course_subject_name']) : null;
        $course_teacher_email_1     = isset($_POST['course_teacher_email_1']) && !empty($_POST['course_teacher_email_1']) ? trim($_POST['course_teacher_email_1']) : null;
        $course_teacher_email_2     = isset($_POST['course_teacher_email_2']) && !empty($_POST['course_teacher_email_2']) ? trim($_POST['course_teacher_email_2']) : null;
        $course_class_1             = isset($_POST['course_class_1']) && !empty($_POST['course_class_1']) ? trim($_POST['course_class_1']) : null;
        $course_class_2             = isset($_POST['course_class_2']) && !empty($_POST['course_class_2']) ? trim($_POST['course_class_2']) : null;

        return new course($course_room_number,$course_day, $course_start, $course_subject_name, [$course_teacher_email_1, $course_teacher_email_2], [$course_class_1, $course_class_2]);
    }
}
