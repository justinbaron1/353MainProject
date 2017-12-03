<?php

include_once("common/user.php");

$sellerId = strip_tags(trim(@$_GET["sellerId"]));

$seller = get_user_by_id($mysqli, $sellerId);
$ads = get_ads_by_user_id($mysqli, $sellerId);

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
            <h1>Ads by <?= $seller["firstName"]." ".$seller["lastName"] ?></h1>
            <table class="table table-hover table-striped">
                <thead>
                    <tr class="ad">
                        <th>Title</th>
                        <th>Price</th>
                        <th>Subcategory</th>
                        <th>Category</th>
                        <th>City</th>
                        <th>Province</th>        
                        <th>Since</th>
                        <th>Ends</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ads as $ad) { ?>
                        <tr class="ad" onclick="document.location.href = '/ad.php?ad_id=<?= $ad["adId"] ?>';">
                            <td><?= $ad["title"] ?></td>
                            <td><?= $ad["price"] ?></td>
                            <td><?= $ad["subCategory"] ?></td>
                            <td><?= $ad["category"] ?></td>
                            <td><?= $ad["city"] ?></td>
                            <td><?= $ad["province"] ?></td>                            
                            <td><?= $ad["startDate"] ?></td>
                            <td><?= $ad["endDate"] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>

</html>
