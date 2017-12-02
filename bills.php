<?php
    include_once("common/user.php");
    
    if(!is_admin($mysqli, $user["userId"])) {
        redirect_index();
        return;
    }

    $credit_bills = get_all_credit_bills($mysqli);
    $debit_bills = get_all_debit_bills($mysqli);
?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>

    <body>
        <?php include("common/navbar.php") ?>
        <div class="container">
            <h1>Bills</h1>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#creditCard">Credit Cards</a></li>
                <li><a data-toggle="tab" href="#debitCard">Debit Cards</a></li>
            </ul>
            <div class="tab-content">
                <div id="creditCard" class="tab-pane fade in active">
                    <?php if(empty($credit_bills)) { ?>
                        <div class="row text-center">
                            There are no credit card bills.
                        </div>
                    <?php } else {?>
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Credit Card Number</td>
                                    <th>Date of payment</th>
                                    <th>User Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($credit_bills as $bill) { ?>
                                    <tr>
                                        <td><?= $bill["amount"] ?>$</td>                                
                                        <td><?= $bill["cardNumber"] ?></td>                                
                                        <td><?= $bill["dateOfPayment"] ?></td>
                                        <td><?= $bill["userId"] ?></td>                            
                                    </tr>
                                                        
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <div id="debitCard" class="tab-pane fade in active">
                    <?php if(empty($debit_bills)) { ?>
                        <div class="row text-center">
                            There are no deit card bills.
                        </div>
                    <?php } else { ?>
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Debit Card Number</td>
                                    <th>Date of payment</th>
                                    <th>User Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($debit_bills as $bill) { ?>
                                    <tr>
                                        <td><?= $bill["amount"] ?>$</td>                                
                                        <td><?= $bill["cardNumber"] ?></td>                                
                                        <td><?= $bill["dateOfPayment"] ?></td>
                                        <td><?= $bill["userId"] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>
