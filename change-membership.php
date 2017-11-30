<?php 
    include_once("common/user.php");
?>
<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container background">
          <h1 class="text-center white-text">Change Membership</h1>
          <form method="POST">
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
