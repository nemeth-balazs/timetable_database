<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__."/../classes/user.php";
include_once __DIR__ . "/../services/service_user.php";

$process_user = new process_user();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_add'])) {
        $process_user ->process_add_user();
    } elseif (isset($_POST['user_delete'])) {
        $process_user ->process_delete_user();
    } elseif (isset($_POST['user_modify'])) {
        $process_user ->process_modify_user();
    }
}

header("Location: ../../ui/pages/page_user.php");

class process_user{
    public function process_add_user(): bool
    {
        $new_user = $this->get_user_add();
        if (!$new_user) return false;

        $is_valid = service_user::user_validate($new_user);
        if (!$is_valid) return false;

        $is_valid = service_user::user_add_to_database($new_user);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Felhasználó hozzáadása sikeres volt!";
        return true;
    }

    public function process_modify_user(): bool
    {
        $new_user = $this->get_user_modify();
        if (!$new_user) return false;

        $is_valid = service_user::user_validate($new_user);
        if (!$is_valid) return false;

        $is_valid = service_user::user_modify_in_database($new_user);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Felhasználó módosítása sikeres volt!";
        return true;
    }

    public function process_delete_user(): bool
    {
        $new_user = $this->get_user_modify();
        if (!$new_user) return false;

        $is_valid = service_user::user_delete_from_database($new_user);
        if (!$is_valid) return false;

        $_SESSION["message"] = "Felhasználó törlése sikeres volt!";
        return true;
    }

    private function get_user_add(): ?user
    {
        $new_user = $this->get_user();
        if ( !$new_user->getUserId() || !$new_user->getUserName() ||
            !$new_user->getUserEmail() || !$new_user->getUserPhone() ||
            !$new_user->getUserPassword_1() || !$new_user->getUserPassword_2() ||
            !$new_user->getUserLevel()){
            $_SESSION["error"] = "Nem megfelelő felhasználói paraméterek!";
            return null;
        }

        return $new_user;
    }

    private function get_user_modify(): ?user
    {
        $new_user = $this->get_user();
        if (!$new_user->getUserId()){
            $_SESSION["error"] = "Nem megfelelő felhasználói paraméterek!";
            return null;
        }

        return $new_user;
    }

    private function get_user(): user
    {
        $user_id            = isset($_POST['user_id']) && !empty($_POST['user_id']) ? trim($_POST['user_id']) : null;
        $user_name          = isset($_POST['user_name']) && !empty($_POST['user_name']) ? trim($_POST['user_name']) : null;
        $user_email         = isset($_POST['user_email']) && !empty($_POST['user_email']) ? trim($_POST['user_email']) : null;
        $user_phone         = isset($_POST['user_phone']) && !empty($_POST['user_phone']) ? trim($_POST['user_phone']) : null;
        $user_password_1    = isset($_POST['user_password_1']) && !empty($_POST['user_password_1']) ? trim($_POST['user_password_1']) : null;
        $user_password_2    = isset($_POST['user_password_2']) && !empty($_POST['user_password_2']) ? trim($_POST['user_password_2']) : null;
        $user_level         = isset($_POST['user_level']) && !empty($_POST['user_level']) ? trim($_POST['user_level']) : null;
        $user_subjects      = isset($_POST['user_subjects']) && !empty($_POST['user_subjects']) ? trim($_POST['user_subjects']) : null;

        return new user($user_id,$user_name, $user_email,$user_phone,$user_password_1,$user_password_2, $user_level,$user_subjects);
    }
}
