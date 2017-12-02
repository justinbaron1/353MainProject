<?php

if ($_POST) {
    $qty = strip_tags(trim(@$_POST["qty"]));
    $affected_rows = 0;

    for($i = 0; $i < $qty; $i++){
        $ad_id = strip_tags(trim(@$_POST["adId_".$i]));
        $rating = strip_tags(trim(@$_POST["rating_".$i]));

        if(empty($rating)){
            $rating = null;
        }
        
        $affected_rows += update_rating($mysqli, $user["userId"], $ad_id, $rating);
    }

    // if($affected_rows > 0){
        $successMessage = "Ratings successfully saved!";
    // }
    // } else {
    //     $errorMessage = "Some ratings were not saved properly.";
    //  }

}

?>