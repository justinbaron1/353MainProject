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
$title = $subCategory = $imageToUpload = $description = $promotionPackage = "";
$promotions = get_promotions($mysqli);
$categories = array(
  'Buy and Sell' => array(
    'clothing','books','electronics','musicalInstruments'
  ),
  'Services' => array(
    'tutors','eventPlanners','photographers','personalTrainers'
  ),
  'Rent' => array(
    'electronics','car','apartments','weddingDresses'
  ),
  'Category4' => array(
    'subCategory1','subCategory2','subCategory3','subCategory4'
  )

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

if ($_POST) {
  $cats = test_input(@$_POST["subCategory"]);

  $promotionPackage = test_input(@$_POST["promotionPackage"]);

  // Either 'create','update' or 'delete'
  include_once("post/ad.php");

  $action = $_POST["action"];
  if ($action === "create" ||
      $action === "update") {

    $user = $_SESSION["user"];
    $user_id = $user["userId"];
    $title = test_input(@$_POST["title"]);
    $price = @$_POST["price"];
    $description = test_input(@$_POST["description"]);
    $type = test_input(@$_POST["type"]);
    // Pray that no categories contain ';'
    list($category, $sub_category) = explode(';', $cats);
    $file = $_FILES["imageToUpload"];
    $ad_id = @$_POST["ad_id"];

    if ($action === "create") {
      $errors = handle_create_ad($user_id, $title, $price, $description,
                                 $category, $sub_category, $type, $file);
      if (empty($errors)) {
        $success = true;
      } else {
        // TODO(tomleb): Redirect to detail view of the ad ?
        error_log(print_r($errors, true));
        header("Location: index.php");
        return;
      }
    } else {
      // TODO(tomleb): Allow only updating of ad if the user is admin OR the ad
      // belongs to the current user
      $errors = handle_update_ad($ad_id, $user_id, $title, $price, $description,
                                 $category, $sub_category, $type, $file);
      if (!empty($errors)) {
        // TODO(tomleb): Handle errors
        var_dump($errors);
      }
    }
  }

} else if ($_GET) {
  $ad_id = @$_GET["ad_id"];
  $ad = get_ad_by_id($mysqli, $ad_id);

  if ($ad) {
    $action = "update";
    $title = $ad["title"];
    $price = $ad["price"];
    $description  = $ad["description"];
    $category     = $ad["category"];
    $sub_category = $ad["subCategory"];
    $type = $ad["type"];
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

function form_group($errors, $label) {
  if (isset($errors[$label])) {
    echo "<div class=\"form-group has-error\"><label class=\"control-label\" for=\"${label}\"> ${errors[$label]}</label>";
  } else {
    echo '<div class="form-group">';
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
              <?php form_group($errors, "title");  ?>
                  Title: <input type="text" name="title" value="<?= $title ?>">
              </div>
              <br><br>
              <?php form_group($errors, "price");  ?>
                  Price: <input type="number" name="price" value="<?= $price ?>">
              </div>
              <br><br>
              <?php form_group($errors, "type");  ?>
                  Type:
                  <br><br>
                  <select name="type">
                  <option value="buy"  <?= select_if_equal($type, 'buy') ?>>Buy</option>
                  <option value="sell" <?= select_if_equal($type, 'sell') ?>>Sell</option>
                  </select>
              </div>
              <?php form_group($errors, "first_name");  ?>
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
              </div>
              <br><br>
              <?php form_group($errors, "promotionPackage");  ?>
                    PromotionPackage:
                    <select name="promotionPackage">
                    <!-- TODO Only allow updating of promotion when not already chosen -->
                    <?php foreach ($promotions as $duration) { ?>
                      <option value="<?= $duration ?>"><?= $duration ?> Days Promotion</option>
                    <?php } ?>
                    </select>
              </div>
              <br><br>
              <?php form_group($errors, "description");  ?>
                  Description: <textarea name="description" rows="5" cols="40"><?= $description ?></textarea>
              </div>
              <br><br>
              <?php form_group($errors, "imageToUpload");  ?>
              Select image to upload:
              <input type="file" name="imageToUpload" id="imageToUpload">
            </div>
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
echo $promotionPackage;
echo "<br>";
echo $description;
echo "<br>";
echo $imageToUpload;
?>

</body>
</html>
