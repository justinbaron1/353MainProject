<?php

function format_address($civicNumber, $street, $city, $postalCode){
    return $civicNumber." ".$street.", ".$city.", ".$postalCode;
}
?>