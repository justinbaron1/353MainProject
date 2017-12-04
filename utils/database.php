<?php

/*
 *
 * For now we assume that every input passed in to any function in this file
 * have been correctly validated and sanitized.
 *
 */

include_once("utils/log.php");

function promotion_exists($mysqli, $ad_id) {
    $query = <<<SQL
SELECT *
FROM AdPromotion
WHERE AdId = ?
SQL;
  $results = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  log_mysqli_error($mysqli);
  return !empty($results);
}

function create_and_link_promotion_package($mysqli, $promotion_package, $ad_id) {
  $query = "CALL createPromotion(?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ii", $ad_id, $promotion_package);
  $stmt->execute();

  log_mysqli_error($mysqli);
  return $mysqli->error;
}

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
  FROM Admin
  WHERE userId = ?
SQL;
  $results = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
  return  !empty($results);
 }

function get_buyerseller_info($mysqli, $user_id){
  $query = <<<SQL
SELECT *
FROM BuyerSeller
WHERE userId = ?
SQL;

  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
  return @$result[0];
}
 
//
 function get_all_membership_plans($mysqli) {
  $query = <<<SQL
SELECT *
FROM MembershipPlan
SQL;

    return fetch_assoc_all_prepared($mysqli, $query);
 }

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
  log_mysqli_error($mysqli);
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
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
  log_mysqli_error($mysqli);
  return $result;
}

function get_ratings_by_user_id($mysqli, $user_id){
  $query = <<<SQL
  SELECT *
  FROM Rating
  JOIN Ad
  ON Rating.adId = Ad.adId
  WHERE userId = ?
SQL;
    $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
    log_mysqli_error($mysqli);
    return $result;
}

function update_rating($mysqli, $user_id, $ad_id, $rating) {
  $query = <<<SQL
UPDATE Rating
SET rating = ?
WHERE userId = ?
AND   adId = ?
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iii", $rating, $user_id, $ad_id);
  $stmt->execute();
  return $mysqli->affected_rows;
}

function get_all_credit_bills($mysqli){
  $query = <<<SQL
  SELECT *
  FROM Bill
  JOIN PaymentMethod
  ON Bill.paymentMethodId = PaymentMethod.paymentMethodId
  JOIN  CreditCard
  ON CreditCard.paymentMethodId = Bill.paymentMethodId
  ORDER BY Bill.dateOfPayment DESC
SQL;
    return fetch_assoc_all_prepared($mysqli, $query);
}

function get_all_debit_bills($mysqli){
  $query = <<<SQL
  SELECT *
  FROM Bill
  JOIN PaymentMethod
  ON Bill.paymentMethodId = PaymentMethod.paymentMethodId
  JOIN DebitCard
  ON DebitCard.paymentMethodId = Bill.paymentMethodId
  ORDER BY Bill.dateOfPayment DESC
SQL;
    return fetch_assoc_all_prepared($mysqli, $query);
}

function get_active_credit_card($mysqli, $user_id){
  $query = <<<SQL
  SELECT *
  FROM  PaymentMethod
  JOIN CreditCard
  ON PaymentMethod.paymentMethodId = CreditCard.paymentMethodId
  WHERE userId = ?
  AND active
SQL;
    $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
    return @$result[0];
}

function get_active_debit_card($mysqli, $user_id){
  $query = <<<SQL
  SELECT *
  FROM  PaymentMethod
  JOIN DebitCard
  ON PaymentMethod.paymentMethodId = DebitCard.paymentMethodId
  WHERE userId = ?
  AND active
SQL;
    $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
    return @$result[0];
}

function change_credit_card($mysqli, $user_id, $expiryYear, $expiryMonth, $cardNumber, $securityCode){
  $query = "CALL createNewCreditCard(?, ?, ?, ?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iiiii", $cardNumber, $securityCode, $expiryMonth, $expiryYear, $user_id);
  $stmt->execute();

  if (log_mysqli_error($mysqli)) {
    return false;
  }
  return true;
}

function change_debit_card($mysqli, $user_id, $expiryYear, $expiryMonth, $cardNumber){
  $query = "CALL createNewDebitCard(?, ?, ?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iiii", $cardNumber, $expiryMonth, $expiryYear, $user_id);
  $stmt->execute();

  if (log_mysqli_error($mysqli)) {
    return false;
  }
  return true;
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
  log_mysqli_error($mysqli);
  return @$result[0];
}

function get_ad_by_id($mysqli, $ad_id) {
  $query = <<<SQL
SELECT *
FROM Ad
LEFT JOIN Ad_AdImage ON Ad.adId = Ad_AdImage.adId
LEFT JOIN AdImage ON Ad_AdImage.adImageUrl = AdImage.url
LEFT JOIN AdPromotion ON AdPromotion.adId = Ad.adId
WHERE Ad.adId = ?
AND   NOT isDeleted
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  return @$result[0];
}

function get_ads_by_user_id($mysqli, $user_id) {
  $query = <<<SQL
SELECT *
FROM Ad
JOIN Users ON Ad.sellerId = Users.userId
JOIN Address ON Users.addressId = Address.addressId
JOIN City ON City.city = Address.city
LEFT JOIN AdPosition
ON AdPosition.adId = Ad.adId
WHERE sellerId = ?
AND   NOT isDeleted
ORDER BY startDate DESC
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "i", [$user_id]);
}

// INFO: We support multiple images by ads but we will
// just return this one for now..
function get_ad_image_by_id($mysqli, $ad_id) {
  $query = <<<SQL
SELECT *
FROM Ad_AdImage
INNER JOIN AdImage ON Ad_AdImage.adImageUrl = AdImage.url
INNER JOIN Ad ON Ad.adId = Ad_AdImage.adId
WHERE Ad.adId = ?
AND   NOT isDeleted
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  log_mysqli_error($mysqli);
  return @$result[0];
}

function get_full_ad_by_id($mysqli, $ad_id) {
  $query = <<<SQL
SELECT *
FROM Ad
JOIN BuyerSeller ON BuyerSeller.userId = Ad.sellerId
JOIN Users ON Users.userId = Ad.sellerId
WHERE adId = ?
AND   NOT isDeleted
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  return @$result[0];
}

function get_ad_images_by_ad_id($mysqli, $ad_id) {
  $query = <<<SQL
SELECT adImageUrl
FROM Ad_AdImage
INNER JOIN Ad ON Ad.adId = Ad_AdImage.adId
WHERE Ad.adId = ?
AND   NOT isDeleted
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  log_mysqli_error($mysqli);
  return $result;
}

function get_stores_by_ad_id($mysqli, $ad_id){
  $query = <<<SQL
  SELECT *
  FROM Ad_Store
  JOIN Store
  ON Ad_Store.storeId = Store.storeId
  JOIN Address
  ON Store.addressId = Address.addressId
  JOIN StoreManager
  ON Store.userId = StoreManager.userId
  WHERE adId = ?
  ORDER BY dateOfRent
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", [$ad_id]);
  log_mysqli_error($mysqli);
  return $result;
}

function do_bills_backup($mysqli){
  $query = "CALL generateBackup()";
  $stmt = $mysqli->prepare($query);
  $stmt->execute();

  if (log_mysqli_error($mysqli->error)) {
    return false;
  }
  return true;
}

// TODO(tomleb): Make a transaction
function create_ad_with_image($mysqli, $user_id, $title, $price, $description,
                              $type, $category, $sub_category, $image_filename) {
  $query = "CALL createAd(?, ?, ?, ?, ?, ?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("isdssss", $user_id, $title, $price, $description, $type, $category, $sub_category);
  $stmt->execute();

  if (log_mysqli_error($mysqli)) {
    return false;
  }

  $ad_id = $mysqli->insert_id;

  if ($image_filename !== '') {
    $result = create_and_link_ad_image($mysqli, $ad_id, $image_filename);
    if (!$result) {
      return false;
    }
  }

  return $ad_id;
}

function create_and_link_ad_image($mysqli, $ad_id, $image_filename) {
  $query = <<<SQL
INSERT INTO AdImage(url) VALUES (?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("s", $image_filename);
  $stmt->execute();

  if (log_mysqli_error($mysqli)) {
    return false;
  }

  $query = <<<SQL
INSERT INTO Ad_AdImage(adImageUrl, adId) VALUES (?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("si", $image_filename, $ad_id);
  $stmt->execute();

  if (log_mysqli_error($mysqli)) {
    return false;
  }

  return true;
}

function is_seller($mysqli, $ad_id, $user_id) {
  $query = <<<SQL
  SELECT *
  FROM Ad
  WHERE  adId = ? 
  AND sellerId = ?
SQL;
  $results = fetch_assoc_all_prepared($mysqli, $query, "ii", [$ad_id, $user_id]);
  log_mysqli_error($mysqli);
  return !empty($results);
}

function can_edit_ad($mysqli, $ad_id, $user_id) {
  return is_seller($mysqli, $ad_id, $user_id) || is_admin($mysqli, $user_id);
}

// TODO(tomleb): Something something transaction
function update_ad_with_image($mysqli, $ad_id, $user_id, $title, $price, $description,
                              $type, $category, $sub_category, $new_image, $old_image) {
  $query = <<<SQL
UPDATE Ad
SET title = ?,
    price = ?,
    description = ?,
    type = ?,
    category = ?,
    subCategory = ?
WHERE adId = ?
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("sdssssi", $title, $price, $description, $type, $category, $sub_category, $ad_id);
  $stmt->execute();

  if ($new_image === '') {
    log_info("No image uploaded, skipping.");
    return [];
  }

  if ($old_image) {
    $query = <<<SQL
UPDATE AdImage
SET url = ?
WHERE url = ?
SQL;
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $new_image, $old_image);
    $stmt->execute();
    log_mysqli_error($mysqli);
  } else {
    create_and_link_ad_image($mysqli, $ad_id, $new_image);
  }

  return $mysqli->error;
}

function delete_ad($mysqli, $ad_id) {
  $query = <<<SQL
UPDATE Ad
SET isDeleted = TRUE
WHERE adId = ?
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $ad_id);
  $stmt->execute();
  log_mysqli_error($mysqli);
  return $mysqli->affected_rows;
}

function create_user($mysqli, $first_name, $last_name, $phone, $email, $password, $address_id) {
  $query = <<<SQL
INSERT INTO Users(firstName, lastName, phoneNumber, email, password, addressId) VALUES (?, ?, ?, ?, ?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("sssssi", $first_name, $last_name, $phone, $email, $password, $address_id);
  $stmt->execute();
  log_mysqli_error($mysqli);
  return $mysqli->insert_id;
}

function create_address($mysqli, $civic_number, $street, $postal_code, $city) {
  $query = <<<SQL
INSERT INTO Address (civicNumber, street, postalCode, city) VALUES (?, ?, ?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ssss", $civic_number, $street, $postal_code, $city);
  $stmt->execute();
  log_mysqli_error($mysqli);
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
INNER JOIN AdPosition ON Ad.adId=AdPosition.adId
WHERE province    = COALESCE($province_param, province)
AND   City.city   = COALESCE($city_param, City.city)
AND   category    = COALESCE($category_param, category)
AND   subCategory = COALESCE($sub_category_param, subCategory)
AND   type        = COALESCE($type_param, type)
AND   CONCAT(firstName, ' ', lastName) LIKE COALESCE($seller_name_param , CONCAT(firstName, ' ', lastName))
AND   NOT isDeleted
AND   endDate >= CURRENT_DATE
ORDER BY position
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, $bind_type, $args);
  log_mysqli_error($mysqli);
  return $result;
}

function get_categories_and_subcategories($mysqli) {
  $query = <<<SQL
SELECT *
FROM SubCategory
SQL;
  $cats = fetch_assoc_all_prepared($mysqli, $query);
  log_mysqli_error($mysqli);
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
  log_mysqli_error($mysqli);
  $result = [];
  foreach ($provs as $prov) {
    $result[$prov['province']][] = $prov['city'];
  }
  return $result;
}

function get_different_ad_types($mysqli){
  $query = <<<SQL
  SELECT DISTINCT type
  FROM Ad
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query);
  log_mysqli_error($mysqli);
  return $result;
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
    log_info('Initializing database connection..');
    $mysqli = connect_database($ini["MYSQL_HOST"], $ini["MYSQL_USER"], $ini["MYSQL_PASSWORD"], $ini["MYSQL_DATABASE"]);
    log_mysqli_error($mysqli);
  }
  return $mysqli;
}

function connect_database($hostname, $user, $password, $database) {
  $mysqli = new mysqli($hostname, $user, $password, $database);
  if ($mysqli->connect_errno) {
    log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
  }
  return $mysqli;
}

?>
