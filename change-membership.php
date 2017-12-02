<?php 
    include_once("common/user.php");
    include_once("post/change-membership.php");

    $memberships = get_all_membership_plans($mysqli);
    $buyerseller_infos = get_buyerseller_info($mysqli, $user["userId"]);
    if(empty($buyerseller_infos)) {
        redirect_index();
        return;
    }

    $possible_memberships = array_filter($memberships, function($cur) use (&$buyerseller_infos) {
        return $cur["name"] != $buyerseller_infos["membershipPlanName"];
    });

?>
<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container text-center">
            <?php if(isset($successMessage)) { ?>
                <div class="alert alert-success">
                    <?= $successMessage ?>
                </div>
            <?php } ?>
            <?php if(isset($errorMessage)) { ?>
                <div class="alert alert-danger">
                    <?= $errorMessage ?>
                </div>
            <?php } ?>
            <h1 class="text-center">Change Membership</h1>
            <form class="form-inline" method="POST">
                <div class="form-group">
                    <span>Change from <b><?=$buyerseller_infos["membershipPlanName"] ?></b> to </span>
                </div>
                <div class="form-group mx-sm-3">
                    <select class="form-control" name="name">
                        <?php foreach($possible_memberships as $curMembership) { ?>
                            <option value="<?= $curMembership["name"] ?>"><?= $curMembership["name"] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Update</button>
            </form>
        </div>
    </body>
</html>
