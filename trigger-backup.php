<?php
    include_once("common/user.php");

    $do = @$_POST["do"];

    if(isset($do) && is_admin($mysqli, $user["userId"])){
        $success = do_bills_backup($mysqli);
        if($success){
            $successMessage = "Backup successful!";
            unset($_GET["do"]);
        }
    }

?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
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
                    <form method="post">
                        <input type="hidden" name="do"/>
                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Process Backup</input>
                    </form>
                </div>
            </div>
        </div>
    </body>

</html>
