<?php
require 'classes/user.php';

$_POST = json_decode(file_get_contents("php://input"), true); // получаем json 

if (isset($_POST["action"])) { // выполняем функцию в зависимости от действия
    if ($_POST["action"] == "logout") {
        User::logout();
        header("Location: login.php");
    }

    $user = new User();
    if ($_POST["action"] == "register") {
        $user->register($_POST);
    } else if ($_POST["action"] == "login") {
        $user->login($_POST);
    }
}
?>