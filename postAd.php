<?php

// session_start();

include_once("common/user.php");
// include_once("utils/database.php");
include("utils/upload.php");
// include_once("utils/user.php");
include_once("utils/validation.php");

$update_success = false;
$ad_id = false;
$action = "create";
$title = $subCategory = $imageToUpload = $description = $promotion_package = "";

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
$image_url = "";

$user = $_SESSION["user"];
$user_id = $user["userId"];

// TODO(tomleb): For some reason, if the file uploaded is too large,
// the request becomes a GET.

if ($_POST) {
  $cats = test_input(@$_POST["subCategory"]);

  $promotion_package = test_input(@$_POST["promotion_package"]);

  // Either 'create','update'
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
    $errors = handle_create_ad($ad_id, $user_id, $title, $price, $description, $category,
                               $sub_category, $type, $file, $promotion_package);
    if (empty($errors)) {
      header("Location: ad.php?ad_id=$ad_id");
      return;
    }
  } else if ($action === "update") {
    $errors = handle_update_ad($ad_id, $user_id, $title, $price, $description,
                               $category, $sub_category, $type, $file, $promotion_package);
    if (!$errors) {
      $update_success = true;
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
    return;
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

<!DOCTYPE HTML>
<html>
<head>
  <?php include_once("common/head.php"); ?>
</head>
<body>
<?php include("common/navbar.php"); ?>
<div class="container background">
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <?php if ($action === "create") { ?>
              <h1 class="text-center white-text">Post a new Ad!</h1>
            <?php } else { ?>
              <h1 class="text-center white-text">Update my Ad!</h1>
            <?php } ?>

            <?php if ($update_success) { ?>
              <div class="alert alert-success" role="alert">
                  <b>Success!</b> Your ad has been updated.
              </div>
            <?php } ?>

            <?php if (!empty($errors) && isset($errors['general'])) { ?>
              <div class="alert alert-error" role="alert">
              <b>Error!</b> <?= $errors['general'] ?>
              </div>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
              <?php if ($ad_id) { ?>
                <input type="hidden" name="ad_id" value="<?= $ad_id ?>">
              <?php } ?>
              <input type="hidden" name="action" value="<?= $action ?>">
              <?php form_group($errors, "title", "Title");  ?>
                <input id="title" placeholder="Title" value="<?= $title ?>" type="text" class="form-control"  name="title">
              </div>
              <?php form_group($errors, "price", "Price");  ?>
                  <input id="price" placeholder="Price" value="<?= $price | 0 ?>" min="0" step="0.01" type="number" class="form-control"  name="price">
              </div>

              <?php form_group($errors, "type", "Type"); ?>
                <select class="form-control" name="type">
                  <option value="buy"  <?= select_if_equal($type, 'buy') ?>>Buy</option>
                  <option value="sell" <?= select_if_equal($type, 'sell') ?>>Sell</option>
                </select>
              </div>

              <?php form_group($errors, "subCategory", "Category"); ?>
                <select class="form-control" name="subCategory">
                  <?php foreach ($categories as $category => $subcategories) { ?>
                    <optgroup label="<?= ucwords($category) ?>">
                      <?php foreach ($subcategories as $subcategory) { ?>
                        <option value="<?= $category ?>;<?= $subcategory ?>" <?= select_if_equal($sub_category, $subcategory) ?>>
                          <?= ucwords($subcategory) ?>
                        </option>
                      <?php } ?>
                    </optgroup>
                  <?php } ?>
                </select>
              </div>

              <?php form_group($errors, "promotion_package", "Promotion package"); ?>
                <?php if (promotion_exists($mysqli, $ad_id)) { ?>
                  <select disabled class="form-control" name="promotion_package_readonly">
                    <option><?= $promotion_package ?> Days Promotion</option>
                  </select>
                <?php } else { ?>
                  <select class="form-control" name="promotion_package">
                    <option value="0" <?= select_if_equal($promotion_package, 0) ?>>No promotion</option>
                    <?php foreach ($promotions as $duration) { ?>
                      <option value="<?= $duration ?>" <?= select_if_equal($promotion_package, $duration) ?>><?= $duration ?> Days Promotion</option>
                    <?php } ?>
                  </select>
                <?php } ?>
              </div>

              <?php form_group($errors, "description", "Description"); ?>
                <textarea class="form-control" name="description" rows="5" cols="40"><?= $description ?></textarea>
              </div>

              <?php form_group($errors, "imageToUpload", "Image"); ?>
                <input type="file" name="imageToUpload" id="imageToUpload" value="asd">
              </div>

            <?php if ($action === "create") { ?>
              <input class="btn btn-default" type="submit" name="submit" value="Create">
            <?php } else { ?>
              <input class="btn btn-default" type="submit" name="submit" value="Update">
            <?php } ?>
            </form>
        </div>
    </div>
</div>
