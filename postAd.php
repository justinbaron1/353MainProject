<?php

// session_start();

include_once("common/user.php");
// include_once("utils/database.php");
include("utils/upload.php");
// include_once("utils/user.php");
include_once("utils/validation.php");

$update_success = false;
$ad_id = false;
$stores = [];
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
$date = "";
$start_date = "";
$end_date = "";
$include_delivery = false;

$user = $_SESSION["user"];
$user_id = $user["userId"];

// TODO(tomleb): For some reason, if the file uploaded is too large,
// the request becomes a GET.

if ($_POST) {
  $cats = sanitize(@$_POST["subCategory"]);

  $promotion_package = sanitize(@$_POST["promotion_package"]);

  // Either 'create','update'
  include_once("post/ad.php");

  $action = $_POST["action"];
  $ad_id = @$_POST["ad_id"];

  if ($action === "create" || $action === "update") {
    $title = sanitize(@$_POST["title"]);
    $price = @$_POST["price"];
    $description = sanitize(@$_POST["description"]);
    $type = sanitize(@$_POST["type"]);
    // Pray that no categories contain ';'
    list($category, $sub_category) = explode(';', $cats);
    $file = $_FILES["imageToUpload"];

    if ($action === "create") {
      $errors = handle_create_ad($ad_id, $user_id, $title, $price, $description, $category,
        $sub_category, $type, $file, $promotion_package);
      if (empty($errors)) {
        header("Location: ad.php?ad_id=$ad_id");
        return;
      }
    } else if ($action === "update") {
      $errors = handle_update_ad($ad_id, $user_id, $title, $price, $description,
        $category, $sub_category, $file, $promotion_package);
      if (!$errors) {
        $update_success = true;
      }
    }
  } else if ($action === "delete") {
    $errors = handle_delete_ad($user_id, $ad_id);
    if (empty($errors)) {
      header("Location: ads.php?delete_success=true");
      return;
    }
  } else if ($action === "rent") {
    $store_id = @$_POST["store_id"];
    $date = @$_POST["date"];
    $start_time = date('H:i', mktime(@$_POST["start_time"], 0));
    $end_time = date('H:i', mktime(@$_POST["end_time"], 0));
    $include_delivery = @$_POST["include_delivery"] === "on";

    $errors = handle_rent_ad_store($ad_id, $store_id, $date, $start_time, $end_time, $include_delivery);
    if (empty($errors)) {
      header("Location: ad.php?ad_id=$ad_id&rent_success=true");
      return;
    }
  }

}

if ($_GET) {
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
      $image_url = $ad["url"];
    }
  } else {
    header("Location: postAd.php");
    return;
  }

  $stores = get_stores_by_ad_id($mysqli, $ad_id);
  $full_ad = get_full_ad_by_id($mysqli, $ad_id);
  $possibleRentDates = [];
  $date = date("Y-m-d", strtotime(str_replace('-','/', $full_ad["startDate"])));

  for(;$date <= $full_ad["endDate"]; $date = date('Y-m-d', strtotime($date. ' + 1 days'))) {
    $canAdd = true;
    foreach($stores as $store){
      if($store["dateOfRent"] == $date){
        $canAdd = false;
      }
    }

    if($canAdd){
      array_push($possibleRentDates, $date);
    }
  }

  $stores = get_stores($mysqli);

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
  <style>
      .have-lb{
          white-space: pre-wrap;
      }
      img {
          width:100%;
      }
  </style>
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

            <?php if ($ad_id && can_edit_ad($mysqli, $ad_id, $user_id)) { ?>
              <form class ="row" method="post">
                <div class="col-md-offset-11">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="ad_id" value="<?= $ad_id ?>">
                  <input class="btn btn-danger" type="submit" name="submit" value="Delete">
                </div>
              </form>
            <?php } ?>

            <?php if ($update_success) { ?>
              <div class="alert alert-success" role="alert">
                  <b>Success!</b> Your ad has been updated.
              </div>
            <?php } ?>

            <?php if (!empty($errors) &&
                     (isset($errors['general']) || isset($errors['delete']) || isset($errors['rent']))) { ?>
              <div class="alert alert-danger" role="alert">
              <?php if (isset($errors['general'])) { ?>
                <b>Error!</b> <?= $errors['general'] ?>
              <?php } ?>
              <?php if (isset($errors['rent'])) { ?>
                <b>Error!</b> <?= $errors['rent'] ?>
              <?php } ?>
              <?php if (isset($errors['delete'])) { ?>
                <b>Error!</b> <?= $errors['delete'] ?>
              <?php } ?>
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

              <?php if ($action === "create") { ?>
                <?php form_group($errors, "type", "Type"); ?>
                  <select class="form-control" name="type">
                    <option value="buy" <?= select_if_equal($type, 'buy') ?>>Buy</option>
                    <option value="sell" <?= select_if_equal($type, 'sell') ?>>Sell</option>
                  </select>
                </div>
              <?php } ?>

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

              <div class="row">
                <div class="col-md-6">
                  <?php form_group($errors, "imageToUpload", "Image"); ?>
                    <input type="file" name="imageToUpload" id="imageToUpload" value="asd">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <label>Current image<label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <?php if($image_url && $image_url !== ''){ ?>
                        <img src="<?= image_to_link($image_url) ?>"/>
                      <?php } else { ?>
                        <img src="http://epaper2.mid-day.com/images/no_image_thumb.gif"/>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>

            <?php if ($action === "create") { ?>
              <input class="btn btn-default" type="submit" name="submit" value="Create">
            <?php } else { ?>
              <input class="btn btn-default" type="submit" name="submit" value="Update">
            <?php } ?>
            </form>

            <!-- Support only rent store after ad created for now -->
            <?php if ($ad_id && $type === "sell") { ?>
              <h1>Rent a store</h1>
              <form method="post">
                <input type="hidden" name="action" value="rent">
                <input type="hidden" name="ad_id" value="<?= $ad_id ?>">

                <?php form_group($errors, "date", "Date of Rent");  ?>
                  <select class="form-control" id="date" name="date">
                    <?php foreach ($possibleRentDates as $date) { ?>
                      <option value="<?= $date ?>"><?= $date ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <?php form_group($errors, "start_time", "Start time");  ?>
                      <div class="row">
                        <div class="col-md-10">
                          <input id="start_time" type="number" value="0" step="1" min="0" max="23" class="form-control"  name="start_time">
                        </div>
                        <div class="col-md-2 text-left">
                          h
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <?php form_group($errors, "end_time", "End time");  ?>
                      <div class="row">
                        <div class="col-md-10">
                          <input id="end_time" type="number" value="0" step="1" min="0" max="23" class="form-control" name="end_time">
                        </div>
                        <div class="col-md-2 text-left">
                          h
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <?php form_group($errors, "store_id", "Store"); ?>
                    <select class="form-control" name="store_id">
                    <?php foreach ($stores as $store) { ?>
                      <option value="<?= $store["storeId"] ?>" <?= select_if_equal("TODO", $store["storeId"]) ?>><?= $store["locationName"] ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <div class="checkbox">
                    <label><input type="checkbox" name="include_delivery"> Include delivery services</label>
                  </div>
                </div>

                <input class="btn btn-default" type="submit" name="submit" value="Rent Store">
              </form>
            <?php } else if ($type === "sell") { ?>
              <h2>To sell your ad in store, edit it later.</h2>
            <?php } ?>


        </div>
    </div>
</div>
