<?php
include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_course.php');
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

<h1>Tanóra felvétele</h1>

<form method="POST" action="../../core/process/process_course.php" accept-charset="utf-8">

  <div class="container_label container_input">
    <label>Teremszám: </label>
    <input type="text" name="course_room_number" placeholder="1EM12"/>
    <br>
    <label>Nap: </label>
    <input type="text" name="course_day" placeholder="kedd"/>
    <br>
    <label>Kezdés: </label>
    <input type="time" name="course_start" placeholder="08:00"/>
    <br>
    <label>Tantárgy neve: </label>
    <input type="text" name="course_subject_name" placeholder="algebra"/>
    <br>
    <label>Oktatók e-mail címei: </label>
    <input type="email" name="course_teacher_email_1" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" placeholder="nagy_bela@school.com"/>
    <br>
    <label></label>
    <input type="email" name="course_teacher_email_2" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" placeholder="nagy_bela@school.com"/>
    <br>
    <label>Osztály évfolyamok: </label>
    <input type="text" name="course_class_1" placeholder="10A"/>
    <br>
    <label></label>
    <input type="text" name="course_class_2" placeholder="10B"/>
    <br>

  <?php messages();?>

  <input type="submit" name="course_add" value="mentés" />

  </div>
  <hr>

</form>

<h1>Tanórák listázása</h1>

<table>
    <tr>
        <th>Teremszám</th>
        <th>Nap</th>
        <th>Kezdés</th>
        <th>Tantárgy neve</th>
        <th>Oktató e-mail címe</th>
        <th>Oktató e-mail címe</th>
        <th>Osztály évfolyam</th>
        <th>Osztály évfolyam</th>
        <th>Tanóra törlése</th>
        <th>Tanóra módosítása</th>
    </tr>

    <?php
    $courses = service_course::course_get_all();
    if (empty($courses)) {
        echo '<tr><td colspan="10">Nincs megjeleníthető adat.</td></tr>';}

    foreach ($courses as $course){
        echo '<tr>';
        echo '<form method="POST" action="../../core/process/process_course.php">';

        echo '<td><input type="text" name="course_room_number" value="' . htmlspecialchars($course->getRoomNumber()) . '" readonly/></td>';
        echo '<td><input type="text" name="course_day" value="' . htmlspecialchars($course->getDay()) . '" readonly/></td>';
        echo '<td><input type="time" name="course_start" value="' . htmlspecialchars($course->getStart()) . '" readonly/></td>';
        echo '<td><input type="text" name="course_subject_name" value="' . htmlspecialchars($course->getSubjectName()) . '" /></td>';
        echo '<td><input type="email" name="course_teacher_email_1" value="' . htmlspecialchars($course->getTeacherEmail_array()[0] ?? '') . '"/></td>';
        echo '<td><input type="email" name="course_teacher_email_2" value="' . htmlspecialchars( $course->getTeacherEmail_array()[1] ?? '') . '" /></td>';
        echo '<td><input type="text" name="course_class_1" value="' . htmlspecialchars($course->getClass_array()[0] ?? '') . '"/></td>';
        echo '<td><input type="text" name="course_class_2" value="' . htmlspecialchars($course->getClass_array()[1] ?? '') . '" /></td>';

        echo '<td><input type="submit" name="course_delete" value="Törlés" class="delete-btn" /></td>';
        echo '<td><input type="submit" name="course_modify" value="Módosítás" class="modify-btn" /></td>';

        echo '</form>';
        echo '</tr>';
    }

    ?>

</table>

<br>
<hr>


</BODY>
</HTML>