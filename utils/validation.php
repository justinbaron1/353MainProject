<?php

/*
 * Very basic validation functions (Could be extended with filter_var's options
 * capabilities)
 */

include_once("utils/database.php");

function sanitize($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function validate_rent_ad_store($ad_id, $store_id, $date, $start_time, $end_time, 
                                $delivery_services) {
  global $mysqli;
  $mysqli = get_database();
  $errors = [];

  $ad = get_ad_by_id($mysqli, $ad_id);
  if (!$ad) {
    $errors["ad_id"] = "Invalid ad";
    return $errors;
  }

  if (!is_ad_type_sell($ad))    { $errors["ad_id"] = "Ad should be type sell"; }
  if (!is_valid_id($store_id)) { $errors["store_id"] = "Invalid store"; }
  if (!is_valid_date($ad, $date, $start_time))     { $errors["date"] = "Invalid date"; }
  if (ad_store_exists($mysqli, $ad_id, $store_id, $date)) {
    $errors["rent"] = "Ad_Store already exists";
  }
  if (!is_valid_time($start_time)) { $errors["start_time"] = "Invalid start time"; }
  if (!is_valid_time($end_time)) { $errors["end_time"] = "Invalid end time"; }
  if (new DateTime($start_time) > new DateTime($end_time)) {
    $errors["start_time"] = "Start time is after end time.";
  }
  // TODO Check start_time < end_time
  return $errors; 
}

function validate_ad_with_type($title, $price, $description,
                            $category, $sub_category, $type) {
  $errors = validate_ad($title, $price, $description, $category, $sub_category);
  if (!is_valid_ad_type($type)) { $errors["type"] = "Invalid type"; }
  return $errors;
}

function validate_ad($title, $price, $description,
                     $category, $sub_category) {
  $errors = [];
  // Very basic validation
  if (empty($title))           { $errors["title"] = "Invalid title"; }
  if (!is_valid_price($price)) { $errors["price"] = "Invalid price"; }
  if (empty($description))     { $errors["description"] = "Invalid description"; }
  if (!is_valid_category_and_subcategory($category, $sub_category)) { $errors["category"] = "Invalid category/subcategory"; }

  return $errors;
}

function validate_registration($first_name, $last_name, $phone, $email, $password, $password_confirmation,
                               $civic_number, $street, $postal_code, $city) {
  global $mysqli;
  $mysqli = get_database();
  $errors = [];

  if (empty($first_name)) { $errors["first_name"] = "Invalid first name."; }
  if (empty($last_name))  { $errors["last_name"] = "Invalid last name."; }
  if (empty($phone)) { $errors["phone"] = "Invalid phone."; }
  if (!is_valid_email($email)) { $errors["email"] = "Invalid email."; }
  if (!is_available_email($mysqli, $email)) { $errors["email"] = "Email already exists."; }
  if (!is_valid_password($password)) {
    $errors["password"] = "Invalid password. Must be 8 characters or more.";
  } else if ($password !== $password_confirmation) { 
    $errors["password"] = "The two passwords are different.";
  }
  if (!is_valid_number($civic_number)) { $errors["civic_number"] = "Invalid civic number."; }
  if (empty($street))                  { $errors["street"] = "Invalid street."; }
  if (!is_valid_postal_code($postal_code))   { $errors["postal_code"] = "Invalid postal code."; }
  if (!is_valid_city($mysqli, $city))  { $errors["city"] = "Invalid city."; }

  return $errors;
}

function is_available_email($mysqli, $email) {
  return !get_user_by_email($mysqli, $email);
}

function is_valid_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// At least 8 characters.. Whatever
function is_valid_password($password) {
  return strlen($password) >= 8;
}

function is_valid_price($price) {
  return (float)$price >= 0;
}

function is_valid_id($id) {
  return (int)$id >= 0;
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

// Not sure what are the valid values
function is_valid_ad_type($type) {
  return $type === 'buy' || $type === 'sell';
}

function is_valid_category_and_subcategory($category, $sub_category) {
  global $mysqli;
  $mysqli = get_database();
  $cats = get_categories_and_subcategories($mysqli);
  return isset($cats[$category]) && in_array($sub_category, $cats[$category]);
}

function is_valid_date($ad, $date, $start_time) {
  $date = new DateTime($date);
  $now = new DateTime();

  if ($date->format('Y-m-d') === $now->format('Y-m-d')) {
    $start = new DateTime($start_time);
    if ($start < $now) {
      return false;
    }
  }

  $date_plus_one = $date->add(new DateInterval('P1D'));

  return $now <= $date_plus_one && $date <= new DateTime($ad["endDate"]);
}

function is_valid_time($time) {
  return $time !== '';
}

function is_ad_type_sell($ad) {
  return $ad["type"] === "sell";
}

?>
