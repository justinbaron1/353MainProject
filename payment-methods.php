<?php

include_once("common/user.php");
include_once("utils/validation.php");
$errors = [];

$cardType = "";
$expiryYear = "";
$expiryMonth = "";
$cardNumber =  "";
$securityCode = "";

if ($_POST) {
    $cardType =            strip_tags(trim(@$_POST["cardType"]));
    $expiryYear =          strip_tags(trim(@$_POST["expiryYear"]));
    $expiryMonth =         strip_tags(trim(@$_POST["expiryMonth"]));
    $cardNumber =          strip_tags(trim(@$_POST["cardNumber"]));
    $securityCode =        strip_tags(trim(@$_POST["securityCode"]));

    if($cardType == "credit"){
        $rows_changed = change_credit_card($mysqli, $user["userId"], $expiryYear, $expiryMonth, $cardNumber, $securityCode);
    } else if($cardType == "debit") {
        $rows_changed = change_debit_card($mysqli, $user["userId"], $expiryYear, $expiryMonth, $cardNumber);        
    }
}

function form_group($errors, $name, $label = null) {
    if (isset($errors[$name])) {
        echo "<div class=\"form-group has-error\"><label class=\"control-label\" for=\"${name}\"> ${errors[$name]} </label>";
    } else if ($label) {
        echo "<div class=\"form-group\"><label class=\"control-label\" for=\"${name}\"> $label </label>";
    } else {
        echo '<div class="form-group">';
    }
}

?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container">
            <h1 class="text-center white-text">Payment Methods</h1>
            <div class="row">
                <div class="col-md-12">
                    <h3>Current payment method</h3>
                </div>
            </div>
            <div class="row">
                <?php if(!empty($credit)){ ?>
                    <div class="col-md-3">
                        <span>Type: Credit card</span>
                    </div>
                    <div class="col-md-3">
                        <span>Expires: <?= $credit["expiryYear"]?>-<?= $credit["expiryMonth"]?></span>
                    </div>
                    <div class="col-md-3">
                        <span>Number: <?= $credit["cardNumber"]?></span>
                    </div>
                    <div class="col-md-3">
                        <span>Security Code: <?= $credit["securityCode"]?></span>
                    </div>
                <?php } else if(!empty($debit)){ ?>
                    <div class="col-md-3">
                        <span>Debit card</span>
                    </div>
                    <div class="col-md-3">
                        <span>Expires: <?= $debit["expiryYear"]?>-<?= $debit["expiryMonth"]?></span>
                    </div>
                    <div class="col-md-6">
                        <span>Number: <?= $debit["cardNumber"]?></span>
                    </div>
                <?php } else { ?>
                    <div class="col-md-12">
                        No active payment method
                    </div>
                <?php } ?>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-12">
                    <h3>Change payment method</h3>
                </div>
            </div>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#creditCard">Credit Card</a></li>
                <li><a data-toggle="tab" href="#debitCard">Debit Card</a></li>
            </ul>
            <div class="tab-content">
                <div id="creditCard" class="tab-pane active">
                    <form method="post">
                        <input type="hidden" name="cardType" value="credit"/>
                        <?php form_group($errors, "expiryYear", "Expiry Year");  ?>
                            <input id="expiryYear" placeholder="2017" type="number" step="1" class="form-control"  name="expiryYear" value="<?= $expiryYear ?>">
                        </div>
                        <?php form_group($errors, "expiryMonth", "Expiry Month");  ?>
                            <input id="expiryMonth" placeholder="03" type="number" step="1" class="form-control"  name="expiryMonth" value="<?= $expiryMonth ?>">
                        </div>
                        <?php form_group($errors, "cardNumber", "Card Number");  ?>
                            <input id="cardNumber" placeholder="XXXXXXXXXXXXX" type="number" step="1" class="form-control"  name="cardNumber" value="<?= $cardNumber ?>">
                        </div>
                        <?php form_group($errors, "securityCode", "Security Code");  ?>
                            <input id="securityCode" placeholder="XXX" type="number" step="1" class="form-control"  name="securityCode" value="<?= $securityCode ?>">
                        </div>
                        <input class="btn btn-default" type="submit" name="submit" value="Change"/>
                    </form>
                </div>
                <div id="debitCard" class="tab-pane">
                    <form method="post">
                        <input type="hidden" name="cardType" value="debit"/>
                        <?php form_group($errors, "expiryYear", "Expiry Year");  ?>
                            <input id="expiryYear" placeholder="2017" type="number" step="1" class="form-control"  name="expiryYear" value="<?= $expiryYear ?>">
                        </div>
                        <?php form_group($errors, "expiryMonth", "Expiry Month");  ?>
                            <input id="expiryMonth" placeholder="03" type="number" step="1" class="form-control"  name="expiryMonth" value="<?= $expiryMonth ?>">
                        </div>
                        <?php form_group($errors, "cardNumber", "Card Number");  ?>
                            <input id="cardNumber" placeholder="XXXXXXXXXXXXX" type="number" step="1" class="form-control"  name="cardNumber" value="<?= $cardNumber ?>">
                        </div>
                        <input class="btn btn-default" type="submit" name="submit" value="Change"/>
                    </form>
                </div>
        </div>
    </body>
</html>
