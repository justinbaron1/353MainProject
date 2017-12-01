<?php 
    $user = @$_SESSION["user"];
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
        <li><a href="postAd.php" class="slide-section"><span class="glyphicon glyphicon-plus"></span> Create an Ad</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= $user["firstName"]." ".$user["lastName"] ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="/profile"><span class="glyphicon glyphicon-user"></span> Profile</a><li>
                <li><a href="/change-membership"><span class="glyphicon glyphicon-cog"></span> Change memberships</a><li>
                <li><a href="/my-ads"><span class="glyphicon glyphicon-list-alt"></span> My Ads</a><li>
                <li><a href="/TODO"><span class="glyphicon glyphicon-tags"></span> Rate my transactions</a><li>

                <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Log out</a><li>
            </ul>
        </li>
    </ul>
</div>
