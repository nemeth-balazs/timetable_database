
<?php
include_once('../pages/menu.php');
include_once('../pages/error_message.php');
include_once('../../core/services/service_user.php');
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

<h1>Felhasználó felvétele</h1>

<form method="POST" action="../../core/process/process_user.php" accept-charset="utf-8">

  <div class="container_label container_input">
    <label>Azonosító: </label>
    <input type="text" name="user_id" placeholder="nagy_bela"/>
    <br>
    <label>Név: </label>
    <input type="text" name="user_name" placeholder="Nagy Béla"/>
    <br>
    <label>E-mail cím: </label>
    <input type="email" name="user_email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" placeholder="nagy_bela@school.com"/>
    <br>
    <label>Telefonszám: </label>
    <input type="tel" name="user_phone" pattern="^\+36\d{9}$" placeholder="+36301234567"/>
    <br>
    <label>Jelszó: </label>
    <input type="password" name="user_password_1" placeholder="******"/>
    <br>
    <label>Jelszó újra: </label>
    <input type="password" name="user_password_2" placeholder="******"/>
    <br>

    <label for="user_level">Felhasználói szint: </label>
    <select name="user_level">
        <option value="user">user</option>
        <option value="admin" >admin</option>
    </select>

    <br>
    <label>Oktatott tárgyak: </label>
    <input type="text" name="user_subjects" placeholder="algebra, történelem"/>
    <br>

  <?php messages();?>

  <input type="submit" name="user_add" value="mentés" />

  </div>
  <hr>

</form>

<h1>Felhasználók listázása</h1>

<table>
    <tr>
    <th>Azonosító</th>
    <th>Név</th>
    <th>E-mail cím</th>
    <th>Telefonszám</th>
    <th>Jelszó</th>
    <th>Jelszó újra</th>
    <th>Felahsználói szint</th>
    <th>Oktatott tárgyak</th>
    <th>Felhasználó törlése</th>
    <th>Felhasználó módosítása</th>
    </tr>

    <?php
    $users = service_user::user_get_all_user_with_subjects();
    if (empty($users)) {
        echo '<tr><td colspan="10">Nincs megjeleníthető adat.</td></tr>';}

    foreach ($users as $user){
        echo '<tr>';
        echo '<form method="POST" action="../../core/process/process_user.php">';

        echo '<td><input type="text" name="user_id" value="' . htmlspecialchars($user->getUserId()) . '" readonly/></td>';
        echo '<td><input type="text" name="user_name" value="' . htmlspecialchars($user->getUserName()) . '" /></td>';
        echo '<td><input type="email" name="user_email" value="' . htmlspecialchars($user->getUserEmail()) . '" /></td>';
        echo '<td><input type="tel" name="user_phone" value="' . htmlspecialchars($user->getUserPhone()) . '" /></td>';
        echo '<td><input type="password" name="user_password_1" value="" /></td>';
        echo '<td><input type="password" name="user_password_2" value="" /></td>';

        echo '<td>
            <select name="user_level">
                <option value="user" ' . ($user->getUserLevel() == 'user' ? 'selected' : '') . '>user</option>
                <option value="admin" ' . ($user->getUserLevel() == 'admin' ? 'selected' : '') . '>admin</option>
            </select>
        </td>';

        echo '<td><textarea name="user_subjects" rows="4" cols="30">' . htmlspecialchars(implode(', ', $user->getUserSubjects())) . '</textarea></td>';

        echo '<td><input type="submit" name="user_delete" value="Törlés" class="delete-btn" /></td>';
        echo '<td><input type="submit" name="user_modify" value="Módosítás" class="modify-btn" /></td>';

        echo '</form>';
        echo '</tr>';
    }

    ?>

</table>

<br>
<hr>

</BODY>
</HTML>