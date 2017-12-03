<?php

if ($_POST) {
    $name = strip_tags(trim(@$_POST["name"]));

    $affecte_rows = change_membership($mysqli, $user["userId"], $name);
    if($affecte_rows > 0){
        $successMessage = "Membership successfully changed!";
    } else {
        $errorMessage = "An error occured during the membership update. Make sure you have a valid payment method."
    }
}

?>