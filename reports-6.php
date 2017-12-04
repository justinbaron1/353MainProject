<?php

include_once("common/user.php");

if(!is_admin($mysqli, $user["userId"])) {
    redirect_index();
    return;
}

$results = report_6($mysqli);
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
        <h1>Report #6</h1>
        <b>For a given physical store manager, generate a report that indicates the daily revenue and
the total number of transactions “online payments” of each physical store belonging to
the manager for the past 15 days.</b>
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
