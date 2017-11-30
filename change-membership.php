<?php 
    include_once("common/user.php");
    
    $memberships = 
?>
<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container background">
            <h1 class="text-center white-text">Change Membership</h1>
            <form method="POST">

                <button type="submit" class="btn btn-default">Update</button>
            </form>
        </div>
    </body>
</html>
