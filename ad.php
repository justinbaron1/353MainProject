<?php 
    include_once("common/user.php");

    $ad_id = strip_tags(trim(@$_GET["ad_id"]));
    $ad = get_full_ad_by_id($mysqli, $ad_id);
    
    if(empty($ad)){
        redirect_index();
        return;
    }

    $stores = get_stores_by_ad_id($mysqli, $ad_id);
    $images_urls = get_ad_images_by_ad_id($mysqli, $ad_id);
    $can_edit = can_edit_ad($mysqli, $ad_id, $user["userId"]);
?>
<html>
    <head>
        <?php include_once("common/head.php") ?>
        <style>
            .have-lb{
                white-space: pre-wrap;
            }
            img {
                width:100%;
            }
        </style>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container">
            <div class="row text-right">
                <div class="col-md-12">
                    <?php if($can_edit) { ?>
                        <a class="btn btn-primary" role="button" href="/postAd.php?ad_id=<?= $ad_id ?>"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row text-center">
                        <?php 
                            if(!empty($images_urls)){
                                foreach($images_urls as $url){ ?>
                                    <div class="col-md-12">
                                        <img src="<?= $url ?>"/>
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="col-md-12">
                                    <img src="http://epaper2.mid-day.com/images/no_image_thumb.gif"/>
                                </div>                       
                        <?php } ?>
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

                    <?php if(!empty($stores)) {
                        include("parts/ad/stores.php");
                    } else { ?>
                        <h3>Not available in store</h3>
                    <?php } ?>
			</div>
        </div>
    </body>
</html>
