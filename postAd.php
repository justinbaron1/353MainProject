<?php

session_start();
include_once("common/user.php");
?>

<!DOCTYPE HTML>
<html>
<head>
    <?php include_once("common/head.php") ?>
</head>
<body>

<?php  include("common/navbar.php")
// define variables and set to empty values
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
);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = test_input($_POST["title"]);
  $subCategory = test_input($_POST["subCategory"]);
  $promotionPackage = test_input($_POST["promotionPackage"]);
  $description = test_input($_POST["description"]);
  $imageToUpload = test_input($_POST["imageToUpload"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<div class="container background">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <h1 class="text-center white-text">Register</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              Title: <input type="text" name="title">
              <br><br>
              Category:
              <select name="subCategory">
                <?php
                foreach ($categories as $category => $subcategories){ ?>
                  <optgroup label="<?= $category ?>">
                    <? foreach ($subcategories as subcategory){ ?>
                      <option value="<?= $subcategories ?>"><?= $subcategory ?> </option>

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
