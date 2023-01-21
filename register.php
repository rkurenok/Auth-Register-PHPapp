<?php
require 'init.php';

if (User::isAuthorized()) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<?php require 'head.php';?>
    <body>
        <div class="form">
            <div class="form__inner">
                <span class="header">Регистрация</span>
                <form action="" method="post">
                    <input type="hidden" id="action" value="register">
                    <div class="field">
                        <label for="">Логин</label>
                        <input type="text" id="login" name="login" placeholder="login" />
                        <span class="error" id="login_error"></span>
                    </div>
                    <div class="field">
                        <label for="">Пароль</label>
                        <input type="password" id="password" name="password" placeholder="password" />
                        <span class="error" id="password_error"></span>
                    </div>
                    <div class="field">
                        <label for="">Повторите пароль</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="confirm password" />
                        <span class="error" id="confirm_password_error"></span>
                    </div>
                    <div class="field">
                        <label for="">Email</label>
                        <input type="email" id="email" name="email" placeholder="email" />
                        <span class="error" id="email_error"></span>
                    </div>
                    <div class="field">
                        <label for="">Имя</label>
                        <input type="text" id="name" name="name" placeholder="name" />
                        <span class="error" id="name_error"></span>
                    </div>
                    <div class="field">
                        <input type="submit" onclick="return submitData();" value="Зарегистрироваться" />
                    </div>
                    <div class="field">
                        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
                    </div>
                </form>
            </div>
        </div>
        <script src="./js/jquery-3.6.3.min.js"></script>
        <script src="./js/app.js"></script>
    </body>
</html>