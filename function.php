<?php
session_start();

$_POST = json_decode(file_get_contents("php://input"), true); // получаем json 

// массив для хранения ошибок
$errorContainer = array();

if (isset($_POST["action"])) { // выполняем функцию в зависимости от действия
    if ($_POST["action"] == "register") {
        register();
    } else if ($_POST["action"] == "login") {
        login();
    }
}

// Register
function register()
{
    // формируем массив с данными
    $post["login"] = clean($_POST["login"]);
    $post["password"] = clean($_POST["password"]);
    $post["email"] = clean($_POST["email"]);
    $post["name"] = clean($_POST["name"]);
    $post["confirm_password"] = clean($_POST["confirm_password"]);

    $data["Users"] = getUsers(); // получаем пользователя

    $errorContainer = validation($post, $data["Users"]); // валидация

    if (empty($errorContainer)) { // если нет ошибок 
        // шифруем пароль и добавляем пользователя
        $id = count($data["Users"]);
        $post["password"] = encryptPassword($post["password"], $id);
        array_pop($post);
        addUser($data["Users"], $post);

        // устанавливаем параметры сессии
        $_SESSION["login"] = $post["login"];
        $_SESSION["id"] = $id;

        echo json_encode(array('result' => 'Register Successful')); // сообщаем об успехе
    } else {
        echo json_encode(array('result' => 'error', 'text_error' => $errorContainer)); // если есть ошибки то отправляем
    }



}

// Login
function login()
{
    // формируем массив с данными
    $login = clean($_POST["login"]);
    $password = clean($_POST["password"]);

    $data["Users"] = getUsers(); // получаем пользователя

    foreach ($data["Users"] as $key => $user) {
        if ($user["login"] == $login) { // если логины совпадают
            $password = encryptPassword($password, $key); // шифрем введенный пароль
            if ($user["password"] == $password) { // если пароли совпадают
                // устанавливаем параметры сессии
                $_SESSION["login"] = $login;
                $_SESSION["id"] = $key;
                echo json_encode(array('result' => 'Login Successful'));
                exit();
            }
        }
    }
    // если сопадений не было - сообщаем об ошибке
    $errorContainer["user_notFound"] = "Неверный логин и/или пароль";
    echo json_encode(array('result' => 'error', 'text_error' => $errorContainer));
}

function clean($value = "") {
    $value = trim($value); // для удаления пробелов из начала и конца строки
    $value = stripslashes($value); // для удаления экранированных символов
    $value = strip_tags($value); // для удаления HTML и PHP тегов
    $value = htmlspecialchars($value); // для преобразования спец символов в HTML-сущности
    
    return $value;
}

function getUsers()
{
    $file = file_get_contents('db.json');
    $data = json_decode($file, true);
    return array_values($data["Users"]);
}

function validation($post, $data, $array = array())
{
    // валидация
    if (strlen($post["login"]) < 6) {
        $array["login"] = "Поле должно содержать минимум 6 символов";
    }
    if (!preg_match('/^([а-яё0-9]{6,}|[a-z0-9]{6,})+$/iu', $post["password"])) {
        $array["password"] = "Пароль должен содержать минимум 6 символов и состоять только из букв и цифр";
    }
    if (!filter_var($post["email"], FILTER_VALIDATE_EMAIL)) {
        $array["email"] = "Email адрес указан не верно";
    }
    if (!preg_match('/^([а-яё]{2,}|[a-z]{2,})+$/iu', $post["name"])) {
        $array["name"] = "Имя должно содержать минимум 2 символа и состоять только из букв";
    }
    foreach ($post as $fieldName => $oneField) {
        if ($oneField == '' || !isset($oneField)) {
            $array[$fieldName] = 'Поле обязательно для заполнения';
        }
    }
    if ($post["password"] !== $post["confirm_password"]) {
        $array["confirm_password"] = "Пароли не совпадают";
    }

    foreach ($data as $user) {
        if ($post["login"] === $user["login"]) {
            $array["login"] = "Такой логин уже существует";
        }
        if ($post["email"] === $user["email"]) {
            $array["email"] = "Такой email уже существует";
        }
    }

    return $array;
}

function encryptPassword($str, $id)
{
    $salt = sha1($id);
    $password = sha1($str . $salt);
    return $password;
}

function addUser($data, $user)
{
    array_push($data, $user);
    file_put_contents("db.json", json_encode($data, JSON_UNESCAPED_UNICODE)); // последний параметр - некодировать многобайтные символы, чтобы кириллица отображалась верно
}
?>