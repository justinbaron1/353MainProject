<?php

session_start();

include_once("utils/database.php");
include_once("utils/user.php");

$user = @$_SESSION["user"];

if (!$user) {
  redirect_index();
  return;
}

$mysqli = get_database();

$ads = get_user_ads($mysqli, $user["userId"]);
$transactions = get_user_transactions($mysqli, $user["userId"]);

function ad_is_expired($ad) {
  date_default_timezone_set('UTC');
  $end_date = new DateTime($ad["endDate"]);
  $now = new DateTime();
  return $end_date < $now;
}

?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container background">
          <h1 class="text-center white-text">Profile</h1>
          <h2>My Ads</h2>
          <table class="table table-condensed">
            <tr>
              <th>Title</th>
              <th>Description</th>
              <th>Price</th>
            </tr>
            <?php foreach ($ads as $ad) { ?>
            <tr <?php echo (ad_is_expired($ad)) ? 'class="danger"' : ''?>>
              <td><?= $ad["title"] ?></td>
              <td><?= $ad["description"] ?></td>
              <td><?= $ad["price"] ?></td>
            </tr>
            <?php } ?>
          </table>
          <h2>My Transactions</h2>
          <table class="table table-condensed">
            <tr>
              <th>Ad</th>
              <th>Amount</th>
              <th>Date</th>
            </tr>
            <?php foreach ($transactions as $transaction) { ?>
            <tr>
              <td><?= $transaction["title"] ?></td>
              <td><?= $transaction["amount"] ?></td>
              <td><?= $transaction["dateOfPayment"] ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
    </body>
</html>
