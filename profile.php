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

if (isset($_POST["action"]) && $_POST["action"] === "rating") {
  foreach ($_POST as $ad_id => $rating) {
    if ($adId === "action" || empty($rating)) {
      continue;
    }

    error_log("Adding a Rating for user '${user["userId"]}' to ad '$ad_id' with value '$rating'");
    rate_ad($mysqli, $user["userId"], $ad_id, $rating);
  }
}

$ads = get_user_ads($mysqli, $user["userId"]);
$transactions = get_user_transactions($mysqli, $user["userId"]);

function ad_is_expired($ad) {
  date_default_timezone_set('UTC');
  $end_date = new DateTime($ad["endDate"]);
  $now = new DateTime();
  return $end_date < $now;
}

// TODO(tomleb): Make different views if no transactions/no ads

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
          <form method="POST">
          <input name="action" value="rating" type="hidden"></input>
          <table class="table table-condensed">
            <tr>
              <th>Ad</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Rating</th>
            </tr>
            <?php foreach ($transactions as $transaction) { ?>
            <tr>
              <td><?= $transaction["title"] ?></td>
              <td><?= $transaction["amount"] ?></td>
              <td><?= $transaction["dateOfPayment"] ?></td>
              <?php if (is_null($transaction["rating"])) { ?>
                <td>
                <select name="<?= $transaction["adId"] ?>">
                  <option value="">Not rated</option>
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
                </td>
              <?php } else { ?>
                <td><?= $transaction["rating"] ?></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </table>
          <input type="submit" value="Save Ratings!"></input>
          </form>
        </div>
    </body>
</html>
