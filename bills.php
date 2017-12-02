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
            <h2>Credit Card paid Bills</h2>
            <?php if(empty($credit_bills)) { ?>
                <div class="row text-center">
                    You haven't published any ad yet.
                </div>
            <?php } else {?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Credit Card Number</td>
                            <th>Date of payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($credit_bills as $bill) { ?>
                            <tr>
                                <td><?= $bill["amount"] ?>$</td>                                
                                <td><?= $bill["cardNumber"] ?></td>                                
                                <td><?= $bill["dateOfPayment"] ?></td>
                            </tr>
                                                   
                        <?php } ?>
                    </tbody>
                </table>
            <h1>Bills</h1>
            <h2>Debit Card paid Bills</h2>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Debit Card Number</td>
                            <th>Date of payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($debit_bills as $bill) { ?>
                            <tr>
                                <td><?= $bill["amount"] ?>$</td>                                
                                <td><?= $bill["cardNumber"] ?></td>                                
                                <td><?= $bill["dateOfPayment"] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </body>
</html>
