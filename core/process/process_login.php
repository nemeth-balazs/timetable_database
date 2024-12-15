<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}

include_once __DIR__."/../classes/user.php";
include_once __DIR__ . "/../services/service_user.php";
include_once __DIR__ . "/../services/service_login.php";

$process_log_in = new process_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_submit'])) {
        $process_log_in->process(false);
    } elseif (isset($_POST['create_account_submit'])) {
        $process_log_in->process(true);
    }
}

class process_login{
    public function process($create_new_account): bool
    {
        $new_user = $this->get_user();

        $is_valid = service_user::user_validate_password($new_user);
        if(!$is_valid){
            $_SESSION["error"] = "Nem megfelelő jelszó!";
            header("Location: ../../ui/pages/login.php");
            return false;
        }

        if($create_new_account) {
            $is_valid = service_user::user_create_new_account_add_to_database($new_user);
            $new_user->setUserLevel("user");
        }
        else {
            $is_valid = service_login::check_user_id_and_password($new_user);}

        if($is_valid)
        {
            $_SESSION["user"] = $new_user;
            header("Location: ../../ui/pages/page_course.php"); exit;
        }
        else {
            header("Location: ../../ui/pages/login.php"); exit;}
    }
    private function get_user(): user
    {
        $user_id            = isset($_POST['login_id']) && !empty($_POST['login_id']) ? trim($_POST['login_id']) : null;
        $user_password_1    = isset($_POST['login_password_1']) && !empty($_POST['login_password_1']) ? trim($_POST['login_password_1']) : null;
        $user_password_2    = isset($_POST['login_password_2']) && !empty($_POST['login_password_2']) ? trim($_POST['login_password_2']) : null;

        return new user($user_id, null, null,null,$user_password_1, $user_password_2, null,null);
    }
}
