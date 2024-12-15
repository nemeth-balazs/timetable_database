<?php
include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_subject_number.php');
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

<form method="POST" action="../../core/process/process_subject_number.php" accept-charset="utf-8">

    <div class="container_label container_input">
        <label>Email: </label>
        <input type="email" name="subject_number_teacher_email" placeholder="Nagy Béla"/>
        <br>
        <?php messages();?>

        <input type="submit" name="get_subject_number_by_teacher" value="keres" />
    </div>
    <hr>

</form>

<h1>Tanárok listázása</h1>

<table>
    <tr>
        <th>Név</th>
        <th>Email</th>
        <th>Telefonszám</th>
        <th>Tantárgyak száma</th>
    </tr>

    <?php
    $results = service_subject_number::get_subject_number_by_teacher();
    if (!$results) {
        echo '<tr><td colspan="10">Nincs megjeleníthető adat.</td></tr>';}
    else{
        foreach ($results as $result){
            echo '<tr>';
            echo '<td><input type="text" name="subject_number_user_name" value="' . $result->getUserName() . '" readonly/></td>';
            echo '<td><input type="text" name="subject_number_user_email" value="' . $result->getUserEmail() . '" readonly/></td>';
            echo '<td><input type="text" name="subject_number_user_phone" value="' . $result->getUserPhone() . '" readonly/></td>';
            echo '<td><input type="text" name="subject_number_count" value="' . $result->getSubjectNumber() . '" readonly/></td>';
            echo '</tr>';
        }
    }

    ?>

</table>

<br>
<hr>

</BODY>
</HTML>