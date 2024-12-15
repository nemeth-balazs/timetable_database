
<?php
include_once('error_message.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Timetable</title>

  <link rel="stylesheet" href="../style/common_form.css">

</head>
<body>

<h1>Órarend adatbázis</h1>

<form method="POST" action="../../core/process/process_login.php" accept-charset="utf-8">

  <div class="container_label container_input">
    <label>Azonosító: </label>
    <input type="text" name="login_id" placeholder="nagy_bela"/>
    <br>
    <label>Jelszó: </label>
    <input type="password" name="login_password_1" placeholder="******"/>
    <br>
    <label>Jelszó újra: </label>
    <input type="password" name="login_password_2" placeholder="******"/>
    <br>
  </div>

 <?php messages();?>

  <input type="submit" name="login_submit" value="Belépés" />
  <input type="submit" name="create_account_submit" value="Létrehozás" />

</form>

</body>
</html>