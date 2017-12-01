<?php 
    include_once("common/user.php");

    $ad_id = strip_tags(trim(@$_GET["ad_id"]));
    $ad = get_full_ad_by_id($mysqli, $ad_id);
    $stores = get_stores_by_ad_id($mysqli, $ad_id)
?>
<html>
    <head>
        <?php include_once("common/head.php") ?>
        <style>
            .have-lb{
                white-space: pre-wrap;
            }
        </style>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <img src=""/>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="row">
                        <h1><?= $ad["title"] ?></h1>
                    </div>
                    </br>
                    <div class="row">
                        <h3>Contact Info</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Email: </strong><span><?= $ad["contactEmail"] ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">                            
                                <strong>Phone: </strong><span><?= $ad["contactPhone"] ?></span>
                            </div>
                        </div>                        
                    </div>
                    <br/>
                    <div class="row">
                        <strong>Type: </strong><span><?= $ad["type"] ?></span>
                    </div>
                    <div class="row">
                        <strong>Category: </strong><span><?= $ad["category"] ?></span>
                    </div>
                    <div class="row">
                        <strong>Subcategory: </strong><span><?= $ad["subCategory"] ?></span>
                    </div>
                    <br/>
                    <div class="row">
                        <strong>Price: </strong><span><?= $ad["price"] ?>$</span>
                    </div>
                    <br/>
                    <div class="row">
                        <strong>Published on </strong><span><?= $ad["startDate"] ?></span>
                    </div>
                    <div class="row">
                        <strong>Available until </strong><span><?= $ad["endDate"] ?></span>
                    </div>
                    </br>
                    <div class="row">
                        <p class="have-lb"><?= $ad["description"] ?></p>
                    </div>
			</div>
        </div>
    </body>
</html>
