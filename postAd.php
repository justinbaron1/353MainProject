<?php

session_start();

include_once("utils/database.php");
include_once("utils/upload.php");
include_once("utils/user.php");
include_once("utils/validation.php");

?>

<!DOCTYPE HTML>
<html>
<head>
    <?php include_once("common/head.php") ?>
</head>
<body>

<?php

include("common/navbar.php");
// define variables and set to empty values
$uploaded = false;
$success = false;
$title = $subCategory = $imageToUpload = $description = $promotionPackage = "";

$mysqli = get_database();
$promotions = array_map(
  function ($promotion) { return $promotion['duration']; },
  get_promotions($mysqli)
);

$categories = get_categories_and_subcategories($mysqli);
$errors = [];

if ($_POST) {
  $cats = test_input(@$_POST["subCategory"]);

  $promotionPackage = test_input(@$_POST["promotionPackage"]);

  $user = $_SESSION["user"];
  $user_id = $user["userId"];
  $title = test_input(@$_POST["title"]);
  // TODO Add price to form
  $price = @$_POST["price"];
  $description = test_input(@$_POST["description"]);
  // TODO Add type to form
  $type = test_input(@$_POST["type"]);
  // Pray that no categories contain ';'
  list($category, $sub_category) = explode(';', $cats);
  $file = $_FILES["imageToUpload"];

  // Very basic validation
  if (empty($title))           { $errors["title"] = "Invalid title"; }
  if (!is_valid_number($price)) { $errors["price"] = "Invalid price"; }
  if (empty($description))     { $errors["description"] = "Invalid description"; }
  if (!is_valid_ad_type($type)) { $errors["type"] = "Invalid type"; }
  if (!is_valid_category_and_subcategory($category, $sub_category)) { $errors["category"] = "Invalid category/subcategory"; }

  if (empty($errors)) {
    $mysqli = get_database();
    $success = create_ad_with_image($mysqli, $user_id, $title, $price, $description, $type, $category, $sub_category, $file['name']);
    if ($success) {
    } else {
      // Not sure what we should do here ?
      return;
    }

    $uploaded = handle_ad_image_upload($mysqli, $file);
  } else {
    // Temporary var_dump
    var_dump($errors);
  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<?php if ($uploaded && $success) { ?>
<h1>Successfully created new ad!</h1>
<?php } ?>

<div class="container background">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <h1 class="text-center white-text">Submit ad</h1>
            <!-- TODO Add labels to input with error class (See register.php) -->
            <form method="post" enctype="multipart/form-data">
              Title: <input type="text" name="title">
              Price: <input type="number" name="price">
              <br><br>
              Type:
              <br><br>
              <select name="type">
                <option value="sell">Sell</option>
                <option value="buy">Buy</option>
              </select>
              Category:
              <select name="subCategory">
                <?php
                foreach ($categories as $category => $subcategories){ ?>
                  <optgroup label="<?= $category ?>">
                    <? foreach ($subcategories as $subcategory){ ?>
                      <option value="<?= $category ?>;<?= $subcategory ?>"><?= $subcategory ?> </option>
                    <?php }

                } ?>

                  </optgroup>
              </select>

              <br><br>
              PromotionPackage:
              <select name="promotionPackage">

              <?php
              foreach ($promotions as $duration) {
              ?>
              <option value="<?= $duration ?>"><?= $duration ?> Days Promotion</option>
              <?php } ?>

              </select>
              <br><br>
              Description: <textarea name="description" rows="5" cols="40"></textarea>
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
echo $promotionPackage;
echo "<br>";
echo $description;
echo "<br>";
echo $imageToUpload;
?>

</body>
</html>
