<?php

include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_teacher_course.php');
?>

<!DOCTYPE HTML>
<HTML>
<HEAD>
    <meta http-equiv="content-type" content="text/html; charset=UTF8" >

    <link rel="stylesheet" href="../style/common_form.css">
    <link rel="stylesheet" href="../style/common_table.css">

</HEAD>
<BODY>

<hr/>
<?php echo menu();?>
<hr/>

<h1>Tanár kiválasztása</h1>

<form method="POST" action="../../core/process/process_teacher_course.php" accept-charset="utf-8">

    <div class="container_label container_input">
        <label>Email: </label>
        <input type="email" name="teacher_course_teacher_email" placeholder="Nagy Béla"/>
        <br>
        <?php messages();?>

        <input type="submit" name="get_teacher_course_by_user_name" value="keres" />
    </div>
    <hr>

</form>

<h1>Tanárok listázása</h1>

<table>
    <tr>
        <th>Név</th>
        <th>Teremszám</th>
        <th>Nap</th>
        <th>Kezdés</th>
        <th>Tantárgy neve</th>
    </tr>

    <?php
    $results = service_teacher_course::get_teacher_course_by_user_name();
    if (!$results) {
        echo '<tr><td colspan="10">Nincs megjeleníthető adat.</td></tr>';}
    else{
        foreach ($results as $result){
            echo '<tr>';
            echo '<td><input type="text" name="teacher_course_user_name" value="' . $result->getUserName() . '" readonly/></td>';
            echo '<td><input type="text" name="teacher_course_room_number" value="' . $result->getRoomNumber() . '" readonly/></td>';
            echo '<td><input type="text" name="teacher_course_day" value="' . $result->getDay() . '" readonly/></td>';
            echo '<td><input type="text" name="teacher_course_start" value="' . $result->getStart() . '" readonly/></td>';
            echo '<td><input type="text" name="teacher_course_subject_name" value="' . $result->getSubjectName() . '" readonly/></td>';
            echo '</tr>';
        }
    }

    ?>

</table>

<br>
<hr>

</BODY>
</HTML>