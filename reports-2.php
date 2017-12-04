<?php

include_once("common/user.php");

if(!is_admin($mysqli, $user["userId"])) {
    redirect_index();
    return;
}

$results = report_2($mysqli);
?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
        <style>
            .ad:hover{
                cursor:pointer;
            }
        </style>
    </head>

    <body>
        <?php include("common/navbar.php") ?>
        <div class="container">
        <h1>Report #2</h1>
        <b>Give the details of the items posted within the last 10 days in buying/selling category.</b>
            <?php if(empty($results)) { ?>
                <div class="row text-center">
                    No result.
                </div>
            <?php } else {?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <?php foreach($results[0] as $field => $value) { ?>
                                <th><?=$field?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $result) { ?>
                            <tr>
                                <?php foreach($result as $value) { ?>
                                    <td><?= $value ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </body>

</html>
