<?php

session_start();

include_once("utils/index.php");

$error = false;

if ($_POST && isset($_POST["email"]) && isset($_POST["password"])) {

  $valid = user_try_login($_POST["email"], $_POST["password"]);
  if (!$valid) {
    $error = 'Unable to login. Please make sure ...';
  }

}

?>


<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container background">
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <h1 class="text-center white-text">Login</h1>

                    <?php if ($error) { ?>
                    <p class="text-danger"> <?=$error?> </p>
                    <?php } ?>

                    <form method="post">
                        <div class="form-group">
                            <input id="email" placeholder="Email" type="email" class="form-control"  name="email">
                        </div>
                        <div class="form-group">
                            <input id="password" placeholder="Password" type="password" class="form-control"  name="password">
                        </div>
                        <button type="submit" class="btn btn-default">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
