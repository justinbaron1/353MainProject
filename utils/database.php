<?php

/*
 *
 * For now we assume that every input passed in to any function in this file
 * have been correctly validated and sanitized.
 *
 */

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
  $result = fetch_assoc_all_prepared($mysqli, $query, "s", $city);
  return @$result[0];
}

function get_user_by_id($mysqli, $id) {
  $query = <<<SQL
SELECT *
FROM  Users
WHERE userId = ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", $id);
  return @$result[0];
}

function get_user_by_email($mysqli, $email) {
  $query = <<<SQL
SELECT *
FROM  Users
WHERE email = ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "s", $email);
  return @$result[0];
}

function get_ad_by_id($mysqli, $ad_id) {
  $query = <<<SQL
SELECT *
FROM Ad
WHERE adId = ?
SQL;
  $result = fetch_assoc_all_prepared($mysqli, $query, "i", $ad_id);
  return @$result[0];
}

function create_ad($mysqli, $user_id, $title, $price, $description, $end_date,
                   $type, $category, $sub_category) {
  $query = <<<SQL
INSERT INTO Ad(sellerId,title,price,description,endDate,type,category,subCategory) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ssisssss", $user_id, $title, $price, $description, $end_date, $type, $category, $sub_category);
  $stmt->execute();
  return $mysqli->insert_id;
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
  $stmt->bind_param("sssssi", $fist_name, $last_name, $phone, $email, $password, $address_id);
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

function search_ad_by_seller_name($mysqli, $name) {
  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Users ON Ad.sellerId = Users.userId
WHERE CONCAT(firstName, ' ', lastName) LIKE ?
SQL;
  $like_name = "%$name%";
  return fetch_assoc_all_prepared($mysqli, $query, "s", $like_name);
}

function search_ad_by_type($mysqli, $type) {
  $query = <<<SQL
SELECT adId, title, category, subCategory, type
FROM Ad
WHERE type = ?
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "s", $type);
}

function search_ad_by_category($mysqli, $category) {
  $query = <<<SQL
SELECT *
FROM Ad
WHERE category = ?
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "s", $category);
}

function search_ad_by_city($mysqli, $city) {
  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Users ON Ad.sellerId = Users.userId
INNER JOIN Address ON Users.addressId = Address.addressId
WHERE city = ?
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "s", $city);
}

function search_ad_by_province($mysqli, $province) {
  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Users ON Ad.sellerId = Users.userId
INNER JOIN Address ON Users.addressId = Address.addressId
INNER JOIN City ON City.city = Address.city
WHERE province = ?
SQL;
  return fetch_assoc_all_prepared($mysqli, $query, "s", $province);
}

// Fetch assoc ALL THE THINGS
function fetch_assoc_all_prepared($mysqli, $query, $bind_type, $bind_param) {
  $stmt = $mysqli->prepare($query);
  if (!$stmt) {
    error_log($mysqli->error);
    return false;
  }
  $stmt->bind_param($bind_type, $bind_param);
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