<?php
require 'DB.php';

session_start();
class User
{
    private $db;
    private $db_table = "Users";

    public $login;
    public $password;
    public $email;
    public $name;

    private $is_authorized = false;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function getUsers()
    {
        $data[$this->db_table] = array_values($this->db->db[$this->db_table]);
        return $data;
    }

    public function createUser()
    {
        $user["login"] = $this->login;
        $user["password"] = $this->password;
        $user["email"] = $this->email;
        $user["name"] = $this->name;

        $users = $this->getUsers();
        array_push($users[$this->db_table], $user);

        $this->db->saveChange($users);
    }

    public function getUser($id)
    {
        $users = $this->getUsers();
        if ($users[$this->db_table][$id]) {
            return $users[$this->db_table][$id];
        }
        return false;
    }

    public function updateUser($id, $post)
    {
        $user = $this->getUser($id);

        $user["login"] = $post["login"];
        $user["password"] = $post["password"];
        $user["email"] = $post["email"];
        $user["name"] = $post["name"];

        $users = $this->getUsers();
        array_push($users, $user);

        $this->db->saveChange($users);
    }

    public function deleteUser($id)
    {
        $user = $this->getUser($id);

        if ($user) {
            $users = $this->getUsers();
            array_splice($users, $id, 1);

            $this->db->saveChange($users);
        }
        return false;
    }

    public static function isAuthorized()
    {
        if (!empty($_SESSION["id"])) {
            return (bool) $_SESSION["id"];
        }
        return false;
    }

    private function encryptPassword($password, $id = 0)
    {
        $salt = sha1($id);
        $password = sha1($password . $salt);

        return $password;
    }

    public function login($post)
    {
        $users = $this->getUsers();

        foreach ($users[$this->db_table] as $key => $user) {
            if ($user["login"] == $post["login"]) { // если логины совпадают
                $password = $this->encryptPassword($post["password"], $key); // шифруем введенный пароль
                if ($user["password"] == $password) { // если пароли совпадают
                    // устанавливаем параметры сессии
                    $this->setSession($key);
                    echo json_encode(array('result' => 'Login Successful'));
                    exit();
                }
            }
        }
        $errorContainer["user_notFound"] = "Неверный логин и/или пароль";
        echo json_encode(array('result' => 'error', 'text_error' => $errorContainer));
    }

    public static function logout()
    {
        if (isset($_SESSION["id"])) {
            $_SESSION = [];
            session_unset();
            session_destroy();

            echo json_encode(array('result' => 'Logout Successful'));
            exit();
        }
    }

    public function register($post)
    {
        $errorArray = array();
        $users = $this->getUsers();

        foreach ($post as $key => $value) {
            $post[$key] = $this->cleanInput($value);
        }

        $errorArray = $this->validation($post);

        if (empty($errorArray)) {
            $id = count($users[$this->db_table]);
            $password = $this->encryptPassword($post["password"], $id);

            $this->login = $post["login"];
            $this->password = $password;
            $this->email = $post["email"];
            $this->name = $post["name"];

            $this->createUser();
            
            $this->setSession($id);

            echo json_encode(array('result' => 'Register Successful'));
            exit();
        }

        echo json_encode(array('result' => 'error', 'text_error' => $errorArray)); // если есть ошибки то отправляем
        exit();
    }

    private function setSession($id = 0)
    {
        $_SESSION["id"] = $id;
        $_SESSION["login"] = $this->login;
    }

    private function validation($data)
    {
        $array = array();
        $users = $this->getUsers();
        // валидация
        if (!preg_match('/^\S{6,}\z/', $data["login"])) {
            $array["login"] = "Поле должно содержать минимум 6 символов без пробелов";
        }
        if (!preg_match('/^(?=.*\d)(?=.*[a-zа-яё])[a-zа-яё\d]{6,}+$/iu', $data["password"])) {
            $array["password"] = "Пароль должен содержать минимум 6 символов и состоять только из букв и цифр";
        }
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $array["email"] = "Email адрес указан не верно";
        }
        if (!preg_match('/^([а-яё]{2,}|[a-z]{2,})+$/iu', $data["name"])) {
            $array["name"] = "Имя должно содержать минимум 2 символа и состоять только из букв";
        }

        foreach ($data as $fieldName => $oneField) {
            if ($oneField == '' || !isset($oneField)) {
                $array[$fieldName] = 'Поле обязательно для заполнения';
            }
        }

        if ($data["password"] !== $data["confirm_password"]) {
            $array["confirm_password"] = "Пароли не совпадают";
        }

        foreach ($users[$this->db_table] as $user) {
            if ($data["login"] === $user["login"]) {
                $array["login"] = "Такой логин уже существует";
            }
            if ($data["email"] === $user["email"]) {
                $array["email"] = "Такой email уже существует";
            }
        }

        return $array;
    }

    private function cleanInput($value) {
        $value = trim($value); // для удаления пробелов из начала и конца строки
        $value = stripslashes($value); // для удаления экранированных символов
        $value = strip_tags($value); // для удаления HTML и PHP тегов
        $value = htmlspecialchars($value); // для преобразования спец символов в HTML-сущности
        
        return $value;
    }
}
?>