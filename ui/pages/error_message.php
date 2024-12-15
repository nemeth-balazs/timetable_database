<?php
if (session_status() === PHP_SESSION_NONE) {session_start();}
function messages()
{
    if (isset($_SESSION["error"])) {
        echo "<div class='error_message'>".$_SESSION["error"]."</div>";
        unset($_SESSION["error"]);
        echo "<br>";
    }

    if (isset($_SESSION["message"])) {
        echo "<div class='message'>".$_SESSION["message"]."</div>";
        unset($_SESSION["message"]);
        echo "<br>";
    }


}
