<?php

session_start();
include_once("utils/user.php");

?>

<!DOCTYPE HTML>
<html>
<head>
    <?php include_once("common/head.php") ?>
</head>
<body>

<?php include("common/navbar.php")
// define variables and set to empty values
$rating = $description = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $rating = test_input($_POST["rating"]);
  $description = test_input($_POST["description"]);
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
            <h2>Rate Transaction</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              Rating:   <input type="range" min="1" max="5" value="5">
              <br><br>
              Description: <textarea name="description" rows="5" cols="40"></textarea>
              <br><br>
              <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>
</div>
<?php
echo $rating;
echo $description;
?>

</body>
</html>
