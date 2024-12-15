<?php
include_once('../../core/classes/user.php');

if (session_status() === PHP_SESSION_NONE) {session_start();}
function menu(): string
{
    $menustr = "";
    $user = null;
    if (isset($_SESSION["user"]) && $_SESSION['user'] instanceof User) {
        $user = $_SESSION['user'];}

    if($user != null && $user->getUserLevel() == "admin") {
        $menustr .= '<span style="color:blue;font-weight:bold; padding:5px">';
        $menustr .= '<a href="page_user.php">Felhasználók</a>';
        $menustr .= '</span>';

        $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
        $menustr .= '<a href="page_classroom.php">Osztályterem</a>';
        $menustr .= '</span>';

        $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
        $menustr .= '<a href="page_class.php">Osztály</a>';
        $menustr .= '</span>';

        $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
        $menustr .= '<a href="page_course.php">Tanórák</a>';
        $menustr .= '</span>';
    }

    $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
    $menustr .= '<a href="page_class_course.php">Tanórák (osztályok)</a>';
    $menustr .= '</span>';

    $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
    $menustr .= '<a href="page_teacher_course.php">Tanórák (tanárok)</a>';
    $menustr .= '</span>';

    $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
    $menustr .= '<a href="page_subject_number.php">Tantárgyak</a>';
    $menustr .= '</span>';

    $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
    $menustr .= '<a href="page_class_headcount.php">Osztály létszám</a>';
    $menustr .= '</span>';

    if($user != null)
    {
        $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
        $menustr .= '<a href="logout.php">Kijelentkezés</a>';
        $menustr .= '</span>';
    }
    else{
        $menustr .= '<span style="color:blue;font-weight:bold; padding:5px;">';
        $menustr .= '<a href="login.php">Bejelentkezés</a>';
        $menustr .= '</span>';
    }

    return $menustr;
}
