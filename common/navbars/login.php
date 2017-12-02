<?php 
    $user = @$_SESSION["user"];
    $buyerseller_infos = get_buyerseller_info($mysqli, $user["userId"]);
    $is_admin = is_admin($mysqli, $user["userId"]);
?>

<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand slide-section" href="/">COMP 353</a>
</div>
<div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav navbar-right">
        <?php if(!empty($buyerseller_infos)) { ?>
            <li><a href="postAd.php" class="slide-section"><span class="glyphicon glyphicon-plus"></span> Create an Ad</a></li>
        <?php } ?>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= $user["firstName"]." ".$user["lastName"] ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="/profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a><li>
                <?php if(!empty($buyerseller_infos)) { ?>
                    <li><a href="/change-membership.php"><span class="glyphicon glyphicon-cog"></span> Change memberships</a><li>
                    <li><a href="/my-ads.php"><span class="glyphicon glyphicon-list-alt"></span> My Ads</a><li>
                    <li><a href="/rate-transaction.php"><span class="glyphicon glyphicon-tags"></span> Rate my transactions</a><li>
                <?php } ?>
                <?php if($is_admin){ ?>
                    <li><a href="/bills.php"><span class="glyphicon glyphicon-list"></span> Bills</a><li>
                    <li><a href="/trigger-backup.php"><span class="glyphicon glyphicon-save"></span> Backup Bills</a><li>
                <?php } ?>
                <li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Log out</a><li>
            </ul>
        </li>
    </ul>
</div>
