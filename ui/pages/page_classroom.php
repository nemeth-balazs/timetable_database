<?php
include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_classroom.php');
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

<h1>Tanterem felvétele</h1>

<form method="POST" action="../../core/process/process_classroom.php" accept-charset="utf-8">

  <div class="container_label container_input">
    <label>Teremszám: </label>
    <input type="text" name="classroom_number" placeholder="1EM12"/>
    <br>
    <label>Férőhely: </label>
    <input type="text" name="classroom_capacity" placeholder="35"/>
    <br>
    <label>Felszereltség: </label>
    <input type="text" name="classroom_equipment" placeholder="digitális tábla, projektor"/>
    <br>

  <?php messages();?>

  <input type="submit" name="classroom_add" value="mentés" />

  </div>
  <hr>
</form>

<h1>Tantermek listázása</h1>

<table>
    <tr>
        <th>Teremszám</th>
        <th>Férőhely</th>
        <th>Felszereltség</th>
        <th>Tanterem törlése</th>
        <th>Tanterem módosítása</th>
    </tr>

    <?php
    $classrooms = service_classroom::classroom_get_all_classroom_with_equipments();
    if (empty($classrooms)) {
        echo '<tr><td colspan="5">Nincs megjeleníthető adat.</td></tr>';}

    foreach ($classrooms as $classroom){
        echo '<tr>';
        echo '<form method="POST" action="../../core/process/process_classroom.php">';

        echo '<td><input type="text" name="classroom_number" value="' . htmlspecialchars($classroom->getClassroomNumber()) . '" readonly/></td>';
        echo '<td><input type="text" name="classroom_capacity" value="' . htmlspecialchars($classroom->getClassroomCapacity()) . '" /></td>';
        echo '<td><textarea name="classroom_equipment" rows="4" cols="30">' . htmlspecialchars(implode(', ', $classroom->getClassroomEquipment())) . '</textarea></td>';

        echo '<td><input type="submit" name="classroom_delete" value="Törlés" class="delete-btn" /></td>';
        echo '<td><input type="submit" name="classroom_modify" value="Módosítás" class="modify-btn" /></td>';

        echo '</form>';
        echo '</tr>';
    }

    ?>

</table>

<br>
<hr>

</BODY>
</HTML>