<?php

session_start();

include_once("common/user.php");
include_once("utils/database.php");
include_once("utils/upload.php");
include_once("utils/user.php");
include_once("utils/validation.php");

$success = false;
$ad_id = false;
$action = "create";
$title = $subCategory = $imageToUpload = $description = $promotion_package = "";

$mysqli = get_database();
$promotions = array_map(
  function ($promotion) { return $promotion['duration']; },
  get_promotions($mysqli)
);

$categories = get_categories_and_subcategories($mysqli);
$errors = [];

$title = "";
$price = "";
$description  = "";
$category     = "";
$sub_category = "";
$type = "";
$promotion_package = 0;

$user = $_SESSION["user"];
$user_id = $user["userId"];

if ($_POST) {
  $cats = test_input(@$_POST["subCategory"]);

  $promotion_package = test_input(@$_POST["promotion_package"]);

  // Either 'create','update' or 'delete'
  include_once("post/ad.php");

  $action = $_POST["action"];
  $title = test_input(@$_POST["title"]);
  $price = @$_POST["price"];
  $description = test_input(@$_POST["description"]);
  $type = test_input(@$_POST["type"]);
  // Pray that no categories contain ';'
  list($category, $sub_category) = explode(';', $cats);
  $file = $_FILES["imageToUpload"];
  $ad_id = @$_POST["ad_id"];

  if ($action === "create") {
    $errors = handle_create_ad($user_id, $title, $price, $description, $category,
                               $sub_category, $type, $file, $promotion_package);
    if (empty($errors)) {
      $success = true;
    } else {
      // TODO(tomleb): Redirect to detail view of the ad ?
        /*
        header("Location: index.php");
        return;
         */
    }
  } else if ($action === "update") {
    // TODO(tomleb): Allow only updating of ad if the user is admin OR the ad
    // belongs to the current user
    $errors = handle_update_ad($ad_id, $user_id, $title, $price, $description,
                               $category, $sub_category, $type, $file, $promotion_package);
    if (!empty($errors)) {
      var_dump($errors);
    }
  }

} else if ($_GET) {
  $ad_id = @$_GET["ad_id"];
  if (can_edit_ad($mysqli, $ad_id, $user_id)) {
    $ad = get_ad_by_id($mysqli, $ad_id);

    if ($ad) {
      $action = "update";
      $title = $ad["title"];
      $price = $ad["price"];
      $description  = $ad["description"];
      $category     = $ad["category"];
      $sub_category = $ad["subCategory"];
      $promotion_package = $ad["duration"];
      $type = $ad["type"];
    }
  } else {
    header("Location: postAd.php");
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function select_if_equal($a, $b) {
  if ($a === $b) {
    echo 'selected';
  }
}

?>

<!DOCTYPE HTML>
<html>
<head>
<?php
  include_once("common/head.php");
  include("common/navbar.php");
?>
</head>
<body>
<?php if ($success) { ?>
<h1>Successfully created new ad!</h1>
<?php } ?>

<div class="container background">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <h1 class="text-center white-text">Submit ad</h1>
            <!-- TODO Add labels to input with error class (See register.php) -->
            <form method="post" enctype="multipart/form-data">
              <?php if ($ad_id) { ?>
                <input type="hidden" name="ad_id" value="<?= $ad_id ?>">
              <?php } ?>
              <input type="hidden" name="action" value="<?= $action ?>">
              Title: <input type="text" name="title" value="<?= $title ?>">
              <br><br>
              Price: <input type="number" name="price" value="<?= $price ?>">
              <br><br>
              Type:
              <br><br>
              <select name="type">
              <option value="buy"  <?= select_if_equal($type, 'buy') ?>>Buy</option>
              <option value="sell" <?= select_if_equal($type, 'sell') ?>>Sell</option>
              </select>
              Category:
              <select name="subCategory">
                <?php foreach ($categories as $category => $subcategories) { ?>
                  <optgroup label="<?= $category ?>">
                  <? foreach ($subcategories as $subcategory) { ?>
                  <option value="<?= $category ?>;<?= $subcategory ?>" <?= select_if_equal($sub_category, $subcategory) ?>>
                      <?= $subcategory ?>
                    </option>
                  <?php } ?>
                  </optgroup>
                <?php } ?>
              </select>

              <br><br>
              PromotionPackage:
              <?php if (promotion_exists($mysqli, $ad_id)) { ?>
                <?= $promotion_package ?>
              <?php } else { ?>
                <select name="promotion_package">
                <!-- TODO Only allow updating of promotion when not already chosen -->
                <option value="0" <?= select_if_equal($promotion_package, 0) ?>>No promotion</option>
                <?php foreach ($promotions as $duration) { ?>
                  <option value="<?= $duration ?>" <?= select_if_equal($promotion_package, $duration) ?>><?= $duration ?> Days Promotion</option>
                <?php } ?>
                </select>
              <?php } ?>
              <br><br>

              Description: <textarea name="description" rows="5" cols="40"><?= $description ?></textarea>
              <br><br>
                  Select image to upload:
              <input type="file" name="imageToUpload" id="imageToUpload">

              <br><br>
              <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>
</div>
<?php
echo "<h2>Your Input:</h2>";
echo $title;
echo "<br>";
echo $subCategory;
echo "<br>";
echo $promotion_package;
echo "<br>";
echo $description;
echo "<br>";
echo $imageToUpload;
?>

</body>
</html>
