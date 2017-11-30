<?php

session_start();

include_once("utils/database.php");
include_once("utils/user.php");

$user = @$_SESSION["user"];

if (!$user) {
  redirect_index();
  return;
}

$mysqli = get_database();
?>