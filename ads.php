<?php

include_once("common/user.php");

include_once("utils/validation.php");

$delete_success = @$_GET["delete_success"];

$category = sanitize(@$_GET["category"]);
$subcategory = sanitize(@$_GET["subcategory"]);
$province = sanitize(@$_GET["province"]);
$city = sanitize(@$_GET["city"]);
$type = sanitize(@$_GET["type"]);
$seller = sanitize(@$_GET["seller"]);

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
            <?php if ($delete_success) { ?>
              <div class="alert alert-success" role="alert">
                  <b>Success!</b> Your ad has been deleted.
              </div>
            <?php } ?>

            <?php include("parts/searchbar.php") ?>

            <table class="table table-hover table-striped">
                <thead>
                    <tr class="ad">
                        <th class="col-md-2">Title</th>
                        <th class="col-md-1">Seller</th>
                        <th class="col-md-1">Price</th>
                        <th class="col-md-1">Subcategory</th>
                        <th class="col-md-1">Category</th>
                        <th class="col-md-1">City</th>
                        <th class="col-md-1">Province</th>        
                        <th class="col-md-2">Since</th>
                        <th class="col-md-1">Ends</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ads as $ad) { ?>
                        <tr class="ad" onclick="document.location.href = '/ad.php?ad_id=<?= $ad["adId"] ?>';">
                            <td><?= $ad["title"] ?></td>
                            <td><a href="/ads-by-seller.php?sellerId=<?= $ad["sellerId"]?>"><?= $ad["firstName"]." ".$ad["lastName"] ?></a></td>
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
