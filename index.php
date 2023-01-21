<?php
require 'init.php';

if (User::isAuthorized()) {
    // $login = $_SESSION["login"];
    $user = new User();
    $user = $user->getUser($_SESSION["id"]);
    $login = $user["login"];
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<?php require 'head.php'; ?>

<body>
    <h2 class="mainPage">Welcome <?php echo $login ?> id: <?php echo ($_SESSION["id"])?>
    </h2>
    <form action="" method="post" id="logout">
        <input type="hidden" id="action" value="logout">
        <div class="field">
            <input type="submit" onclick="return submitData();" value="Выход">
        </div>
    </form>
    <script src="./js/jquery-3.6.3.min.js"></script>
    <script src="./js/app.js"></script>
</body>

</html>