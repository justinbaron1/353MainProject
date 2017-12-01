<?php

include_once("common/user.php");

$category = strip_tags(trim(@$_GET["category"]));
$subcategory = strip_tags(trim(@$_GET["subcategory"]));
$province = strip_tags(trim(@$_GET["province"]));
$city = strip_tags(trim(@$_GET["city"]));
$type = strip_tags(trim(@$_GET["type"]));
$seller = strip_tags(trim(@$_GET["seller"]));

$ads = search_ad($mysqli, $province, $city, $category, $subcategory, $type, $seller);
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
            <?php include("parts/searchbar.php") ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr class="ad">
                        <th>Title</th>
                        <th>Seller</th>
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
                        <tr class="ad" onclick="document.location.href = '/ad?ad_id=<?= $ad["adId"] ?>';">
                            <td><?= $ad["title"] ?></td>
                            <td><?= $ad["firstName"]." ".$ad["lastName"] ?></td>
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
