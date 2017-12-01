<?php

include_once("common/user.php");

$category = strip_tags(trim(@$_GET["category"]));
$subcategory = strip_tags(trim(@$_GET["subcategory"]));
$province = strip_tags(trim(@$_GET["province"]));
$city = strip_tags(trim(@$_GET["city"]));
$seller = strip_tags(trim(@$_GET["seller"]));

// $ads = get_ads($mysqli, $category, $subcategory, $province, $city, $seller);
?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
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
                        <th>Since</th>
                        <th>Ends</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ads as $ad) { ?>
                        <tr onclick="document.location.href = '/ad?ad_id=<?=$ad[adId] ?>';">
                            <td><?= $ad["title"] ?></td>
                            <td><?= $ad["seller"] ?></td>
                            <td><?= $ad["price"] ?></td>
                            <td><?= $ad["subCategory"] ?></td>
                            <td><?= $ad["category"] ?></td>
                            <td><?= $ad["startDate"] ?></td>
                            <td><?= $ad["endDate"] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>

</html>