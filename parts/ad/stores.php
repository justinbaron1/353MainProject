<?php include_once("utils/formatter.php"); ?>
<div class="row">
    <h3>Store availability</h3>
</div>
<?php foreach($stores as $store) {?>
    <div class="row">
        <div class="col-md-12">
            <h4><b><?= $store["dateOfRent"] ?></b></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <b><?= $store["timeStart"] ?></b> to <b><?= $store["timeEnd"] ?></b>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <span><?= format_address($store["civicNumber"], $store["street"], $store["city"], $store["postalCode"]) ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            Includes delivery services: <b><?= $store["includesDeliveryServices"] > 0 ? "Yes":"No" ?></b>
        </div>
    </div>
    <br/>
<?php } ?>
