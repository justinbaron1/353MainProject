<?php

include_once("database.php");

function redirect_index() {
  header("Location: index.php");
}

function user_try_login($email, $password) {
  $mysqli = get_database();
  $user = get_user_by_email($mysqli, $email, $password);
  if ($user && password_verify($password, $user["password"])) {
    unset($user["password"]);
    $_SESSION["user"] = $user;
    error_log("Successfully logged in user: ${user["userId"]}");
    return true;
  }

  error_log("Failed to login user with email: '$email'");
  return false;
}

function redirect_login_not_logged_in() {
  if (!user_is_logged_in()) {
    header("Location: index.php");
    return;
  }
}

function user_is_logged_in() {
  return @$_SESSION["user"];
}

?>
