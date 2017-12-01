<?php

session_start();

    include_once("utils/user.php");
    include_once("utils/database.php");

    $error = false;

    if ($_POST && isset($_POST["email"]) && isset($_POST["password"])) {

        $valid = user_try_login($_POST["email"], $_POST["password"]);
        if (!$valid) {
            $error = 'Unable to login. Please make sure ...';
        }

    }

    //$mysqli = get_database();

//    if($_POST['action'] == 'triggerTheBackup') {
//        if (!$mysqli->query("CALL p(generateBackup())")) {
//            echo "Echec lors de l'appel à la procédure stockée : (" . $mysqli->errno . ") " . $mysqli->error;
//        }
//    }
?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
        <link rel="stylesheet" type="text/css" href="css/triggerbackup.css">
        <script src="js/triggerbackup.js"></script>
    </head>

    <body>
    <?php include("common/navbar.php") ?>
    <div class="wrapper">
        <div class="container background">

            <p class="success_message">Hello this is a test success message</p>
            <button onclick="backup_transactions()" id="backup_button" class="backup_button" type="button">Trigger A Backup</button>

        </div>
    </div>

    </body>

</html>
