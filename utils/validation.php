<?php

/*
 * Very basic validation functions (Could be extended with filter_var's options
 * capabilities)
 */

function is_valid_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// At least 8 characters.. Whatever
function is_valid_password($password) {
  return strlen($password) >= 8;
}

function is_valid_number($number) {
  return filter_var($number, FILTER_VALIDATE_INT);
}

// TODO(tomleb): Validate format as well
function is_valid_postal_code($postal_code) {
  return strlen($postal_code) === 7;
}

function is_valid_city($mysqli, $city) {
  if (empty($city)) return false;

  $result = get_city_by_name($mysqli, $city);
  return !empty($result);
}

function is_valid_rating($rating) {
  return $rating >= 0 && $rating <= 5;
}

?>
