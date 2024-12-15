<?php
include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_class_course.php');
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

<h1>Osztály kiválasztása</h1>

<form method="POST" action="../../core/process/process_class_course.php" accept-charset="utf-8">

    <div class="container_label container_input">
        <label>Évfolyam: </label>
        <input type="text" name="class_course_year" placeholder="12"/>
        <br>
        <label>Betűjel: </label>
        <input type="text" name="class_course_letter" placeholder="A"/>
        <br>

        <?php messages();?>

        <input type="submit" name="get_class_course_by_year_and_letter" value="keres" />

    </div>
    <hr>

</form>

<h1>Osztályok listázása</h1>

<table>
    <tr>
        <th>Évfolyam</th>
        <th>Betűjel</th>
        <th>Teremszám</th>
        <th>Nap</th>
        <th>Kezdés</th>
        <th>Tantárgy neve</th>
    </tr>

    <?php
    $results = service_class_course::get_class_course_by_year_and_letter();
    if (!$results) {
        echo '<tr><td colspan="10">Nincs megjeleníthető adat.</td></tr>';}
    else{
        foreach ($results as $result){
            echo '<tr>';
            echo '<td><input type="text" name="class_course_year" value="' . $result->getYear() . '" readonly/></td>';
            echo '<td><input type="text" name="class_course_letter" value="' . $result->getLetter() . '" readonly/></td>';
            echo '<td><input type="text" name="class_course_room_number" value="' . $result->getRoomNumber() . '" readonly/></td>';
            echo '<td><input type="text" name="class_course_day" value="' . $result->getDay() . '" readonly/></td>';
            echo '<td><input type="text" name="class_course_start" value="' . $result->getStart() . '" readonly/></td>';
            echo '<td><input type="text" name="class_course_subject_name" value="' . $result->getSubjectName() . '" readonly/></td>';
            echo '</tr>';
        }
    }

    ?>

</table>

<br>
<hr>

</BODY>
</HTML>