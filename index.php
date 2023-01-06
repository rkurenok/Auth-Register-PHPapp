<?php
require 'function.php';

if(isset($_SESSION["id"])) {
    $login = $_SESSION["login"];
}
else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Главная</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <body>
        <h2 class="mainPage">Welcome <?php echo $login ?> <a href="logout.php">Выйти</a></h2>
    </body>
</html>