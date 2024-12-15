<?php
include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_class.php');
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

<h1>Osztály felvétele</h1>

<form method="POST" action="../../core/process/process_class.php" accept-charset="utf-8">

  <div class="container_label container_input">
    <label>Évfolyam: </label>
    <input type="text" name="class_year" placeholder="12"/>
    <br>
    <label>Betűjel: </label>
    <input type="text" name="class_letter" placeholder="A"/>
    <br>
    <label>Osztály létszám: </label>
    <input type="text" name="class_headcount" placeholder="28"/>
    <br>
    <label>Kezdés éve: </label>
    <input type="text" name="class_start_year" placeholder="2020"/>
    <br>
    <label>Osztályfőnök e-mail címe: </label>
    <input type="email" name="class_headmaster_email" placeholder="nagy_bela@school.com"/>
    <br>
    <label>Specializáció: </label>
    <input type="text" name="class_division" placeholder="music"/>
    <br>

 <?php messages();?>

  <input type="submit" name="class_add" value="mentés" />
  </div>

  <hr>

</form>

<h1>Osztályok listázása</h1>

<table>
    <tr>
        <th>Évfolyam</th>
        <th>Betűjel</th>
        <th>Osztály létszám</th>
        <th>Kezdés éve</th>
        <th>Osztályfőnök e-mail címe</th>
        <th>Specializáció</th>
        <th>Osztály törlése</th>
        <th>Osztály módosítása</th>
    </tr>

    <?php
    $classes = service_class::class_get_all();
    if (empty($classes)) {
        echo '<tr><td colspan="8">Nincs megjeleníthető adat.</td></tr>';}

    foreach ($classes as $class){
        echo '<tr>';
        echo '<form method="POST" action="../../core/process/process_class.php">';

        echo '<td><input type="text" name="class_year" value="' . htmlspecialchars($class->getYear()) . '" readonly/></td>';
        echo '<td><input type="text" name="class_letter" value="' . htmlspecialchars($class->getLetter()) . '" readonly/></td>';
        echo '<td><input type="text" name="class_headcount" value="' . htmlspecialchars($class->getHeadcount()) . '" /></td>';
        echo '<td><input type="text" name="class_start_year" value="' . htmlspecialchars($class->getStartYear()) . '"/></td>';
        echo '<td><input type="text" name="class_headmaster_email" value="' . htmlspecialchars($class->getHeadmasterEmail()) . '"/></td>';
        echo '<td><input type="text" name="class_division" value="' . htmlspecialchars($class->getDivision()) . '"/></td>';

        echo '<td><input type="submit" name="class_delete" value="Törlés" class="delete-btn" /></td>';
        echo '<td><input type="submit" name="class_modify" value="Módosítás" class="modify-btn" /></td>';

        echo '</form>';
        echo '</tr>';
    }

    ?>

    <br>
    <hr>

</table>




</BODY>
</HTML>