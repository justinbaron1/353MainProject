<?php

function get_user($mysqli, $email, $password) {
  $query = <<<SQL
SELECT *
FROM  Users
WHERE email = ?
AND   password = ?
SQL;
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();
  $result = $result->fetch_assoc();
  $stmt->close();
  return $result;
}

// TODO(tomleb): Check for errors
// TODO(tomleb): Encrypt password
function create_account($mysqli, $first_name, $last_name, $phone, $email, $password , $address_id) {
  $query = <<<SQL
INSERT INTO Users VALUES (?, ?, ?, ?, ?, ?);
SQL;
  // $stmt->bind_param($bind_type, $bind_param);
  $stmt->execute();
  return $mysqli->errno;
}

function create_address($mysqli, $civic_number, $street, $postal_code, $city) {
  $query = <<<SQL
INSERT INTO Address VALUES (?, ?, ?, ?);
SQL;
  // $stmt->bind_param($bind_type, $bind_param);
  $stmt->execute();
  return $mysqli->errno;
}

function search_ad_by_seller_name($mysqli, $name) {
  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Users ON Ad.sellerId = Users.userId
WHERE name ILIKE '%?%'
SQL;
  return fetch_assoc_prepared($mysqli, $query, "s", $name);
}

function search_ad_by_type($mysqli, $type) {
  $query = <<<SQL
SELECT adId, title, category, subCategory, type,
FROM Ad
WHERE type = ?
SQL;
  return fetch_assoc_prepared($mysqli, $query, "s", $type);
}

function search_ad_by_category($mysqli, $category) {
  $query = <<<SQL
SELECT *
FROM Ad
WHERE category = ?
SQL;
  return fetch_assoc_prepared($mysqli, $query, "s", $category);
}

function search_ad_by_city($mysqli, $city) {
  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Users ON Ad.sellersId = Users.userId
INNER JOIN Address ON Users.addressID = Address.addressID
WHERE city = ?
SQL;
  return fetch_assoc_prepared($mysqli, $query, "s", $city);
}

function search_ad_by_province($mysqli, $province) {
  $query = <<<SQL
SELECT *
FROM Ad
INNER JOIN Users ON Ad.sellersId = Users.userId
INNER JOIN Address ON Users.addressID = Address.addressID
INNER JOIN City ON City.city = Users.city
WHERE province = ?
SQL;
  return fetch_assoc_prepared($myqli, $query, "s", $province);
}

// Fetch assoc ALL THE THINGS
function fetch_assoc_prepared($mysqli, $query, $bind_type, $bind_param) {
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param($bind_type, $bind_param);
  $stmt->execute();
  $result = $stmt->get_result();
  $result = $result->fetch_assoc();
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
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
  return $mysqli;
}


?>
