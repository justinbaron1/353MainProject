<?php 
    include_once("common/user.php");

    $buyerseller_infos = get_buyerseller_info($mysqli, $user["userId"]);
    if(empty($buyerseller_infos)) {
        redirect_index();
        return;
    }
    $ads = get_ads_by_user_id($mysqli, $user["userId"]);
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
            <h1>My Ads</h1>
            <?php if(empty($ads)) { ?>
                <div class="row text-center">
                    You haven't published any ad yet.
                </div>
            <?php } else {?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr class="ad">
                            <th>Title</th>
                            <th>Price</th>
                            <th>Subcategory</th>
                            <th>Category</th>   
                            <th>Since</th>
                            <th>Ends</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $opening_date = null;
                        $current_date = new DateTime();
                        foreach($ads as $ad) { 
                            $opening_date = new DateTime($ad["endDate"]);?>
                            <tr class="ad" onclick="document.location.href = '/postAd?ad_id=<?= $ad["adId"] ?>';">
                                <td><?= $ad["title"] ?></td>
                                <td><?= $ad["price"] ?></td>
                                <td><?= $ad["subCategory"] ?></td>
                                <td><?= $ad["category"] ?></td>                         
                                <td><?= $ad["startDate"] ?></td>
                                <td><?= $ad["endDate"] ?></td>
                                <td>
                                    <?php if ($opening_date > $current_date) {?>
                                        <span class="label label-success"> Active </span>
                                    <?php } else { ?>
                                        <span class="label label-danger"> Inactive </span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </body>

</html>
