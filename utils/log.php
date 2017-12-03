<?php

function log_mysqli_info($message) {
  log_info("Mysqli info: $message");
}

function log_mysqli_error($mysqli) {
  if ($mysqli->error) {
    log_info("Mysqli error: ". $mysqli->error);
    return true;
  }
  return false;
}

function log_info($message) {
  $bt = debug_backtrace();
  // Skip first element (will always be log_info)
  array_shift($bt);

  if(!isset($bt[0])) return;
  $file = $bt[0]["file"];

  $bt = array_reverse($bt);
  $bt = array_map('format_trace', $bt);
  $bt_line = implode('|', $bt);

  // Not 100% robust but gets the job done
  $length = strlen(getcwd()) + 1;
  $file = substr($file, $length);

  error_log("[$bt_line][$file] $message");
}

function format_trace($trace) {
  return "${trace["function"]}:${trace["line"]}";
}

?>
