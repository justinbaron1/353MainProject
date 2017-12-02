<?php 
    include_once("common/user.php");
    
    $buyerseller_infos = get_buyerseller_info($mysqli, $user["userId"]);
    if(empty($buyerseller_infos)) {
        redirect_index();
        return;
    }

    include_once("post/rate-transaction.php");
    
    $ratings = get_ratings_by_user_id($mysqli, $user["userId"]);
?>
<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container">
            <?php if(isset($successMessage)) { ?>
                <div class="alert alert-success">
                    <?= $successMessage ?>
                </div>
            <?php } ?>
            <?php if(isset($errorMessage)) { ?>
                <div class="alert alert-danger">
                    <?= $errorMessage ?>
                </div>
            <?php } ?>
            <h1>Rate Transactions</h1>
            <form class="form-inline" method="POST">
                <input type="hidden" name="qty" value="<?= count($ratings) ?>"/>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr class="ad">
                            <th>Ad Id</th>
                            <th>Title</th>
                            <th>Rating  </th>
                        </tr>
                    </thead>
                    <tbody>
                
                    <?php foreach($ratings as $i => $rating){ ?>

                        <tr>
                            <td><input type="hidden" name="adId_<?=$i?>" value="<?= $rating["adId"]?>"/><?= $rating["adId"]?></td>
                            <td><?= $rating["title"]?></td>
                            <td>
                            <select class="form-control" name="rating_<?=$i?>">
                                <option <?= !isset($rating["rating"]) ? "selected" : "" ?> value="">No rating</option>
                                <option <?= $rating["rating"] == 1 ? "selected" : "" ?> value="1">1</option>
                                <option <?= $rating["rating"] == 2 ? "selected" : "" ?> value="2">2</option>
                                <option <?= $rating["rating"] == 3 ? "selected" : "" ?> value="3">3</option>
                                <option <?= $rating["rating"] == 4 ? "selected" : "" ?> value="4">4</option>
                                <option <?= $rating["rating"] == 5 ? "selected" : "" ?> value="5">5</option>                                
                            </select>    
                            </td>                        
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
                <div class="row text-right">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
