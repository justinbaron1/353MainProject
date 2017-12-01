<?php

/*
 *
 * For now we assume that every input passed in to any function in this file
 * have been correctly validated and sanitized.
 *
 */

function change_membership($mysqli, $user_id, $name){
  $query = <<<SQL
  UPDATE BuyerSeller
  SET membershipPlanName = ?
  WHERE userId = ?
SQL;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("si", $name, $user_id);
    $stmt->execute();
    return $mysqli->affected_rows;
}

 function is_admin($mysqli, $user_id){
    $query = <<<SQL
  SELECT *
  FROM admin
  WHERE userId = ?
SQL;
  $results = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
  return  !empty($results);
 }

function get_buyerseller_info($mysqli, $user_id){
  $query = <<<SQL
SELECT *
FROM buyerseller
WHERE userId = ?
SQL;

  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
  return @$result[0];
}
 
//
 function get_all_membership_plans($mysqli) {
  $query = <<<SQL
SELECT *
FROM membershipplan
SQL;

    return fetch_assoc_all_prepared($mysqli, $query);
 }

// TODO(tomleb): Better error handling
// INFO: We allow rating only if the current rating is NULL..
// This check could be done in the database as well
function rate_ad($mysqli, $user_id, $ad_id, $rating) {
  $query = <<<SQL
UPDATE Rating
SET rating = ?
WHERE userId = ?
AND   adId = ?
AND   rating IS NULL
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iii", $rating, $user_id, $ad_id);
  $stmt->execute();
  return $mysqli->affected_rows;
}

function get_promotions($mysqli) {
  $query = <<<SQL
SELECT *
FROM Promotion
SQL;
  return fetch_assoc_all_prepared($mysqli, $query);
}

function get_user_ads($mysqli, $user_id) {
  $query = <<<SQL
SELECT *
FROM Ad
WHERE sellerId = ?
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
}

function get_user_transactions($mysqli, $user_id) {
  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Transaction ON Transaction.adId = Ad.adId
INNER JOIN Bill ON Bill.billId = Transaction.billId
INNER JOIN PaymentMethod ON PaymentMethod.paymentMethodId = Bill.paymentMethodId
LEFT JOIN Rating ON Rating.adId = Ad.adId
WHERE Rating.userId = ? AND
      PaymentMethod.userId = ?
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "ii", [$user_id, $user_id]);
}

function get_transaction_by_ad_and_user($mysqli, $ad_id, $user_id) {
  $query = <<<SQL
SELECT *
FROM Transaction
INNER JOIN Bill ON Transaction.billId = Bill.billId
INNER JOIN PaymentMethod ON Bill.paymentMethodId = PaymentMethod.paymentMethodId
WHERE adId = ? AND userId = ?
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "ii", [$ad_id, $user_id]);
}

function get_all_city($mysqli) {
  $query = <<<SQL
SELECT *
FROM  City
SQL;
  $stmt = $mysqli->prepare($query);
  if (!$stmt) {
    error_log($mysqli->error);
    return false;
  }
  $stmt->execute();
  $result = $stmt->get_result();
  $result = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $result;
}

function get_city_by_name($mysqli, $city) {
  $query = <<<SQL
SELECT *
FROM  City
WHERE city LIKE ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "s", [$city]);
  return @$result[0];
}

function get_user_by_id($mysqli, $id) {
  $query = <<<SQL
SELECT *
FROM  Users
WHERE userId = ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$id]);
  return @$result[0];
}

function get_user_by_email($mysqli, $email) {
  $query = <<<SQL
SELECT *
FROM  Users
WHERE email = ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "s", [$email]);
  return @$result[0];
}

function get_ad_by_id($mysqli, $ad_id) {
  $query = <<<SQL
SELECT *
FROM Ad
WHERE adId = ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  return @$result[0];
}

function get_ads_by_user_id($mysqli, $user_id) {
  $query = <<<SQL
SELECT *
FROM Ad
WHERE sellerId = ?
ORDER BY startDate DESC
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
}


function get_full_ad_by_id($mysqli, $ad_id) {
  $query = <<<SQL
SELECT *
FROM Ad
JOIN buyerseller
ON buyerseller.userId = ad.sellerId
WHERE adId = ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  return @$result[0];
}

function get_ad_images_by_ad_id($mysqli, $ad_id) {
  $query = <<<SQL
  SELECT adImageUrl
  FROM ad_adimage
  WHERE adId = ?
SQL;
    return fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
}

function get_stores_by_ad_id($mysqli, $ad_id){
  $query = <<<SQL
  SELECT *
  FROM ad_store
  JOIN store
  ON ad_store.storeId = store.storeId
  JOIN address
  ON store.addressId = address.addressId
  JOIN storemanager
  ON store.userId = storemanager.userId
  WHERE adId = ?
  ORDER BY dateOfRent
SQL;
    return fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
}

function do_bills_backup($mysqli){
  $query = "CALL generateBackup()";
  $stmt = $mysqli->prepare($query);
  $stmt->execute();

  if ($mysqli->error) {
    return false;
  }
  return true;
}

// TODO(tomleb): Make a transaction
// TODO(tomleb): Better error handling
function create_ad_with_image($mysqli, $user_id, $title, $price, $description,
                              $type, $category, $sub_category, $image_filename) {
  $query = <<<SQL
INSERT INTO Ad(sellerId,title,price,description,type,category,subCategory) VALUES (?, ?, ?, ?, ?, ?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("isissss", $user_id, $title, $price, $description, $type, $category, $sub_category);
  $stmt->execute();

  if ($mysqli->error) {
    error_log($mysqli->error);
    return false;
  }

  $ad_id = $mysqli->insert_id;

  $query = <<<SQL
INSERT INTO AdImage(url) VALUES (?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("s", $image_filename);
  $stmt->execute();

  if ($mysqli->error) {
    error_log($mysqli->error);
    return false;
  }

  $query = <<<SQL
INSERT INTO Ad_AdImage(adImageUrl, adId) VALUES (?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("si", $image_filename, $ad_id);
  $stmt->execute();

  if ($mysqli->error) {
    error_log($mysqli->error);
    return false;
  }

  $mysqli->close();
  return true;
}

function is_seller($mysqli, $ad_id, $user_id) {
  $query = <<<SQL
  SELECT *
  FROM ad
  WHERE  adId = ? 
  AND sellerId = ?
SQL;
  $results = fetch_assoc_all_prepared($mysqli, $query, "ii", [$ad_id, $user_id]);
  return  !empty($results);
}

function can_edit_ad($mysqli, $ad_id, $user_id) {
  return is_seller($mysqli, $ad_id, $user_id) || is_admin($mysqli, $user_id);
}

// TODO(tomleb): Allow update a subset of attributes ? Don't think we need this
// feature for the project..
// TODO(tomleb): Make sure the user is either admin, or owner of the ad.
function update_ad($mysqli, $ad_id, $user_id, $title, $price, $description, $startDate,
                   $type, $category, $sub_category) {
  $query = <<<SQL
UPDATE Ad
SET sellerId = ?,
    title = ?,
    price = ?,
    description = ?,
    endDate = ?,
    type = ?,
    category = ?,
    subCategory = ?
WHERE adId = ?
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ssisssssi", $user_id, $title, $price, $description, $end_date, $type, $category, $sub_category, $ad_id);
  $stmt->execute();
  return $mysqli->affected_rows;
}

function delete_ad($mysqli, $ad_id) {
  $query = <<<SQL
DELETE FROM Ad
WHERE adId = ?
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $ad_id);
  $stmt->execute();
  return $mysqli->affected_rows;
}

// TODO(tomleb): Better error handling
function create_user($mysqli, $first_name, $last_name, $phone, $email, $password, $address_id) {
  $query = <<<SQL
INSERT INTO Users(firstName, lastName, phoneNumber, email, password, addressId) VALUES (?, ?, ?, ?, ?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("sssssi", $first_name, $last_name, $phone, $email, $password, $address_id);
  $stmt->execute();
  return $mysqli->insert_id;
}

// TODO(tomleb): Better error handling
function create_address($mysqli, $civic_number, $street, $postal_code, $city) {
  $query = <<<SQL
INSERT INTO Address (civicNumber, street, postalCode, city) VALUES (?, ?, ?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ssss", $civic_number, $street, $postal_code, $city);
  $stmt->execute();
  return $mysqli->insert_id;
}

function maybe_add_search_ad_param($value, &$args, &$bind_type) {
  $result = "NULL";
  if (!empty($value)) {
    $result = "?";
    $args[] = $value;
    $bind_type .= "s";
  }
  return $result;
}

function search_ad($mysqli, $province, $city, $category, $sub_category, $type, $seller_name) {
  $args = [];
  $bind_type = "";
  $province_param     = maybe_add_search_ad_param($province,     $args, $bind_type);
  $city_param         = maybe_add_search_ad_param($city,         $args, $bind_type);
  $category_param     = maybe_add_search_ad_param($category,     $args, $bind_type);
  $sub_category_param = maybe_add_search_ad_param($sub_category, $args, $bind_type);
  $type_param         = maybe_add_search_ad_param($type,         $args, $bind_type);

  $seller_name_param = "NULL";
  if (!empty($seller_name)) {
    $seller_name_param = "?";
    $args[] = "%$seller_name%";
    $bind_type .= "s";
  }

  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Users ON Ad.sellerId = Users.userId
INNER JOIN Address ON Users.addressId = Address.addressId
INNER JOIN City ON City.city = Address.city
WHERE province    = COALESCE($province_param, province)
AND   City.city   = COALESCE($city_param, City.city)
AND   category    = COALESCE($category_param, category)
AND   subCategory = COALESCE($sub_category_param, subCategory)
AND   type        = COALESCE($type_param, type)
AND   CONCAT(firstName, ' ', lastName) LIKE COALESCE($seller_name_param , CONCAT(firstName, ' ', lastName))
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, $bind_type, $args);
}

function get_categories_and_subcategories($mysqli) {
  $query = <<<SQL
SELECT *
FROM SubCategory
SQL;
  $cats = fetch_assoc_all_prepared($mysqli, $query);
  $result = [];
  foreach ($cats as $cat) {
    $result[$cat['category']][] = $cat['subCategory'];
  }
  return $result;
}

function get_provinces_and_cities($mysqli){
  $query = <<<SQL
  SELECT *
  FROM City
SQL;
  $provs = fetch_assoc_all_prepared($mysqli, $query);
  $result = [];
  foreach ($provs as $prov) {
    $result[$prov['province']][] = $prov['city'];
  }
  return $result;
}

function get_different_ad_types($mysqli){
  $query = <<<SQL
  SELECT DISTINCT type
  FROM ad
SQL;
  return fetch_assoc_all_prepared($mysqli, $query);
}

function to_reference_values($array) {
  $result = [];
  foreach ($array as $key => $value) {
    $result[$key] = &$array[$key];
  }
  return $result;
}

function fetch_assoc_all_prepared($mysqli, $query, $bind_type = '', $bind_params = []) {
  $stmt = $mysqli->prepare($query);
  if (!$stmt) {
    error_log($mysqli->error);
    return false;
  }

  // Because splat operator is in php 5.6 and we're using 5.5 ..
  if (!empty($bind_params)) {
    $args = array_merge([$bind_type], $bind_params);
    call_user_func_array([$stmt, 'bind_param'], to_reference_values($args));
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $result = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $result;
}

function get_database() {
  global $mysqli;
  if (!$mysqli) {
    // INFO Heh, it's a .env file but a simple one so we can parse it as .ini file.. for now..
    $ini = parse_ini_file("sikrits.env");
    error_log('Initializing database connection..');
    $mysqli = connect_database($ini["MYSQL_HOST"], $ini["MYSQL_USER"], $ini["MYSQL_PASSWORD"], $ini["MYSQL_DATABASE"]);
  }
  return $mysqli;
}

function connect_database($hostname, $user, $password, $database) {
  $mysqli = new mysqli($hostname, $user, $password, $database);
  if ($mysqli->connect_errno) {
    error_log("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
  }
  return $mysqli;
}

?>
