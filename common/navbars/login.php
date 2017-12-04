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
                <?php if(!empty($buyerseller_infos)) { ?>
                    <li><a href="/payment-methods.php"><span class="glyphicon glyphicon-credit-card"></span> Payment Methods</a><li>
                    <li><a href="/change-membership.php"><span class="glyphicon glyphicon-cog"></span> Change memberships</a><li>
                    <li><a href="/my-ads.php"><span class="glyphicon glyphicon-list-alt"></span> My Ads</a><li>
                    <li><a href="/rate-transaction.php"><span class="glyphicon glyphicon-tags"></span> Rate my transactions</a><li>
                <?php } ?>
                <?php if($is_admin){ ?>
                    <li><a href="/bills.php"><span class="glyphicon glyphicon-list"></span> Bills</a><li>
                    <li><a href="/trigger-backup.php"><span class="glyphicon glyphicon-save"></span> Backup Bills</a><li>
                <?php } ?>
                <li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Log out</a><li>
                <?php if($is_admin){ ?>
                    <li role="presentation" class="divider"></li>
                    <li><a href="/reports-1.php"><span class="glyphicon glyphicon-th-list"></span> Report #1</a><li>
                    <li><a href="/reports-2.php"><span class="glyphicon glyphicon-th-list"></span> Report #2</a><li>
                    <li><a href="/reports-3.php"><span class="glyphicon glyphicon-th-list"></span> Report #3</a><li>
                    <li><a href="/reports-4.php"><span class="glyphicon glyphicon-th-list"></span> Report #4</a><li>
                    <li><a href="/reports-5.php"><span class="glyphicon glyphicon-th-list"></span> Report #5</a><li>
                    <li><a href="/reports-6.php"><span class="glyphicon glyphicon-th-list"></span> Report #6</a><li>
                    <li><a href="/reports-7.php"><span class="glyphicon glyphicon-th-list"></span> Report #7</a><li>
                    <li><a href="/reports-8.php"><span class="glyphicon glyphicon-th-list"></span> Report #8</a><li>
                    <li><a href="/reports-9.php"><span class="glyphicon glyphicon-th-list"></span> Report #9</a><li>
                    <li><a href="/reports-10.php"><span class="glyphicon glyphicon-th-list"></span> Report #10</a><li>
                <?php } ?>
            </ul>
        </li>
    </ul>
</div>
