<!DOCTYPE HTML>
<html>
<head>
    <?php include_once("common/head.php") ?>
</head>
<body>

<?php  include("common/navbar.php")
// define variables and set to empty values
$title = $subCategory = $imageToUpload = $description = $promotionPackage = "";

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
                <optgroup label="Buy and Sell">
                  <option value="clothing">Clothing</option>
                  <option value="books">Books</option>
                  <option value="electronics">Electronics</option>
                  <option value="musicalInstruments">Musical Instruments</option>
                </optgroup>
                <optgroup label="Services">
                  <option value="tutors">Tutors</option>
                  <option value="eventPlanners">Event Planners</option>
                  <option value="photographers">Photographers</option>
                  <option value="personalTrainers">PersonalTrainers</option>
                </optgroup>
                <optgroup label="Rent">
                  <option value="electronics">Electronics</option>
                  <option value="car">Car</option>
                  <option value="apartments">Apartments</option>
                  <option value="weddingDresses">Wedding Dresses</option>
                </optgroup>
                <optgroup label="Category4">
                  <option value="subCategory1">subCategory1</option>
                  <option value="subCategory2">subCategory2</option>
                  <option value="subCategory3">subCategory3</option>
                  <option value="subCategory4">subCategory4</option>
                </optgroup>
              </select>

              <br><br>
              PromotionPackage:
              <select name="promotionPackage">
                <option value="7Days">7 Days Promotion!</option>
                <option value="14Days">14 Days Promotion</option>
                <option value="30Days">30 Days Promotion</option>
              </select>
              <br><br>
              Description: <textarea name="description" rows="5" cols="40"></textarea>
              <br><br>
              <form action="upload.php" method="post" enctype="multipart/form-data">     <!-- Modify action -->
                  Select image to upload:
                  <input type="file" name="imageToUpload" id="imageToUpload">
                  <input type="submit" value="Upload Image" name="submit">
              </form>

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
