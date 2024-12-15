<?php
include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_class_headcount.php');
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

<h1>Osztály kiválasztása (MAX)</h1>

<form method="POST" action="../../core/process/process_class_headcount.php" accept-charset="utf-8">

    <div class="container_label container_input">
        <label>Évfolyam: </label>
        <input type="text" name="class_headcount_max_year" placeholder="12"/>
        <br>
        <label>Betűjel: </label>
        <input type="text" name="class_headcount_max_letter" placeholder="A"/>
        <br>

        <input type="submit" name="get_class_max_headcount" value="keres" />

    </div>
    <hr>

</form>

<h1>Osztály kiválasztása (SUM)</h1>

<form method="POST" action="../../core/process/process_class_headcount.php" accept-charset="utf-8">

    <div class="container_label container_input">
        <label>Évfolyam: </label>
        <input type="text" name="class_headcount_sum_year" placeholder="12"/>
        <br>
        <label>Betűjel: </label>
        <input type="text" name="class_headcount_sum_letter" placeholder="A"/>
        <br>

        <input type="submit" name="get_class_sum_headcount" value="keres" />

    </div>
    <hr>

</form>

<?php messages(); ?>

<h1>Osztály létszám (MAX)</h1>

<table>
    <tr>
        <th>Évfolyam</th>
        <th>Betűjel</th>
        <th>Osztály létszáma</th>
    </tr>

    <?php
    $results = service_class_headcount::get_class_max_headcount();
    if (!$results) {
        echo '<tr><td colspan="10">Nincs megjeleníthető adat.</td></tr>';}
    else{
        foreach ($results as $result){
            echo '<tr>';
            echo '<td><input type="text" name="class_headcount_max_year" value="' . $result->getYear() . '" readonly/></td>';
            echo '<td><input type="text" name="class_headcount_max_letter" value="' . $result->getLetter() . '" readonly/></td>';
            echo '<td><input type="text" name="class_headcount_max_number" value="' . $result->getHeadcount() . '" readonly/></td>';
            echo '</tr>';
        }
    }

    ?>

</table>

<h1>Osztályok létszám (SUM)</h1>

<table>
    <tr>
        <th>Évfolyam</th>
        <th>Betűjel</th>
        <th>Osztály létszáma</th>
    </tr>

    <?php
    $results = service_class_headcount::get_class_sum_headcount();
    if (!$results) {
        echo '<tr><td colspan="10">Nincs megjeleníthető adat.</td></tr>';}
    else{
        foreach ($results as $result){
            echo '<tr>';
            echo '<td><input type="text" name="class_headcount_sum_year" value="' . htmlspecialchars($result->getYear()) . '" readonly/></td>';
            echo '<td><input type="text" name="class_headcount_sum_letter" value="' . htmlspecialchars($result->getLetter()) . '" readonly/></td>';
            echo '<td><input type="text" name="class_headcount_sum_number" value="' . htmlspecialchars($result->getHeadcount()) . '" readonly/></td>';
            echo '</tr>';
        }
    }

    ?>

</table>

<br>
<hr>





</BODY>
</HTML>