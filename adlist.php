<?php

session_start();

include_once("utils/user.php");
include_once("utils/database.php");

$error = false;

if ($_POST && isset($_POST["email"]) && isset($_POST["password"])) {

    $valid = user_try_login($_POST["email"], $_POST["password"]);
    if (!$valid) {
        $error = 'Unable to login. Please make sure ...';
    }

}

//$mysqli = get_database();
//$ads = get_frontpage_ads($mysqli, $user["userId"]); //TODO : implement get_frontpage_ads function to get all ads in DB

// JUST TO SHOW AD's VARIABLES, OTHER THAN THAT, IGNORE THIS
//$ads = array(
//      'Ad1' => array(
//           'id','title','price','description', 'start_date', 'end_date'
//         )
//        );

function format_ad_info($data) {
      $data = trim($data);
      $data = stripslashes($data);
      return $data;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $id = format_ad_info($_POST["id"]);
      $title = format_ad_info($_POST["title"]);
      $price = format_ad_info($_POST["price"]);
      $description = format_ad_info($_POST["description"]);
      $start = format_ad_info($_POST["start_date"]);
      $imageToUpload = format_ad_info($_POST["end_date"]);
    }

?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
            <link rel="stylesheet" type="text/css" href="css/adlist.css">
    </head>

    <body>
    <?php include("common/navbar.php") ?>
        <div class="container background">

            <div>
                <input class="searchbox" name="frontpage_search" type="text" placeholder="Search..">
                <input class="searchbutton" id="submit" type="submit" value="Search">
            </div>

            <table class="table_data">
                <tr class="ad">
                    <td>Ad ID</td>
                    <td>Ad Title</td>
                    <td>Ad Price</td>
                    <td>Ad Description</td>
                    <td>Ad Start Date</td>
                    <td>Ad End Date</td>
                </tr>

                <!-- Populate Adlist here-->
                <div class="ad_container">
                    <?php foreach ($ads as $ad) { ?>
                        <tr>
                            <td class="single_ad"><?= $ad["id"] ?></td>
                            <td class="single_ad"><?= $ad["title"] ?></td>
                            <td class="single_ad"><?= $ad["price"] ?></td>
                            <td class="single_ad"><?= $ad["description"] ?></td>
                            <td class="single_ad"><?= $ad["start_date"] ?></td>
                            <td class="single_ad"><?= $ad["end_date"] ?></td>
                        </tr>
                    <?php } ?>

            </table>

            </div>

        </div>

    </body>

</html>