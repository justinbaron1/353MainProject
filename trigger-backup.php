<?php
    include_once("common/user.php");

    $do = @$_GET["do"];

    if(isset($do) && is_admin($mysqli, $user["userId"])){
        $success = do_bills_backup($mysqli);
        if($success){
            $successMessage = "Backup successful!";
        }
    }

?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
        <script src="js/triggerbackup.js"></script>
    </head>

    <body>
    <?php include("common/navbar.php") ?>
    <div class="wrapper">
        <div class="container">
            <?php if(isset($successMessage)) { ?>
                <div class="alert alert-success">
                    <?= $successMessage ?>
                </div>
            <?php } ?>
            <h1>Backup Bills</h1>
            <br/>
            <p>Welcome to the "Backup Bills" page. By clicking the following button, you will create a backup of the existing transactions.</p>
            <br/>
            <div class="text-center">
                <a class="btn btn-primary" role="button" href="/trigger-backup?do"><span class="glyphicon glyphicon-save"></span> Process Backup</a>
            </div>
        </div>
    </div>

    </body>

</html>
