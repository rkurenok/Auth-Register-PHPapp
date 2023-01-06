<?php
require 'function.php';

if (isset($_SESSION["id"])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Вход</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <body>
        <div class="form">
            <div class="form__inner">
                <span class="header">Вход</span>
                <form action="" method="post">
                    <input type="hidden" id="action" value="login">
                    <div class="field">
                        <span class="error" id="user_notFound_error"></span>
                    </div>
                    <div class="field">
                        <label for="">Логин</label>
                        <input type="text" id="login" name="login" placeholder="login" /> <br>
                    </div>
                    <div class="field">
                        <label for="">Пароль</label>
                        <input type="password" id="password" name="password" placeholder="password" /> <br>
                    </div>
                    <div class="field">
                        <input type="submit" onclick="return submitData();" value="Войти" />
                    </div>
                    <div class="field">
                        <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
                    </div>
                </form>
            </div>
        </div>
        <script src="./js/jquery-3.6.3.min.js"></script>
        <script src="./js/app.js"></script>
    </body>
</html>